"""
deploy.py -- Git push local + pull na VPS via SSH
Uso: python deploy.py "mensagem do commit"
"""

import sys
import subprocess
import paramiko

# -- Configuracao ------------------------------------------
VPS_HOST   = "85.31.61.143"
VPS_USER   = "root"
VPS_PASS   = "Um57121214@123"
VPS_DIR    = "/home/deploy/agendamento"
GIT_BRANCH = "crm"
# ----------------------------------------------------------


def run_local(cmd: list[str], cwd: str = None) -> str:
    result = subprocess.run(cmd, capture_output=True, text=True, cwd=cwd)
    out = (result.stdout + result.stderr).strip()
    if result.returncode != 0:
        raise RuntimeError(f"Comando local falhou: {' '.join(cmd)}\n{out}")
    return out


def ssh_run(client: paramiko.SSHClient, cmd: str) -> tuple[str, int]:
    print(f"  $ {cmd}")
    _, stdout, stderr = client.exec_command(cmd)
    exit_code = stdout.channel.recv_exit_status()
    out = stdout.read().decode("utf-8", errors="replace").strip()
    err = stderr.read().decode("utf-8", errors="replace").strip()
    combined = (out + "\n" + err).strip()
    if combined:
        safe = combined.encode("ascii", errors="replace").decode("ascii")
        print(f"    {safe}")
    return combined, exit_code


def git_push(commit_msg: str, project_dir: str):
    print("\n[Git] Adicionando arquivos...")
    run_local(["git", "add", "-A"], cwd=project_dir)

    print("[Git] Criando commit...")
    try:
        out = run_local(["git", "commit", "-m", commit_msg], cwd=project_dir)
        print(f"  {out.splitlines()[0]}")
    except RuntimeError as e:
        if "nothing to commit" in str(e):
            print("  Nada para commitar -- usando ultimo commit.")
        else:
            raise

    print(f"[Git] Pushing para origin/{GIT_BRANCH}...")
    out = run_local(["git", "push", "origin", GIT_BRANCH], cwd=project_dir)
    print(f"  {out.splitlines()[-1] if out else 'ok'}")


def vps_deploy():
    print(f"\n[VPS] Conectando a {VPS_HOST}...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(VPS_HOST, username=VPS_USER, password=VPS_PASS, timeout=15)
    print("  Conectado!")

    cmds = [
        f"cd {VPS_DIR} && git pull origin {GIT_BRANCH}",
        f"docker exec agendamento_app php artisan config:cache",
        f"docker exec agendamento_app php artisan route:cache",
        f"docker exec agendamento_app php artisan view:clear",
    ]

    print("\n[Docker] Executando na VPS...")
    for cmd in cmds:
        out, code = ssh_run(client, cmd)
        if code != 0 and "nothing to commit" not in out and "Already up to date" not in out:
            print(f"  AVISO: Exit code {code}")

    client.close()
    print("\n[OK] Deploy concluido!")


if __name__ == "__main__":
    import os

    project_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    commit_msg  = " ".join(sys.argv[1:]) if len(sys.argv) > 1 else "deploy: atualizacao"

    print(f"[Info] Projeto: {project_dir}")
    print(f"[Info] Commit:  {commit_msg}")

    git_push(commit_msg, project_dir)
    vps_deploy()

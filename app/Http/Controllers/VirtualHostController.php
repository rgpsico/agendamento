<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VirtualHostController extends Controller
{
    protected $path = '/etc/apache2/sites-available';

  public function index()
{
    if (!File::exists($this->path)) {
        return view('admin.virtualhosts.index', ['vhosts' => []]);
    }

    $files = collect(File::files($this->path))
        ->filter(fn($file) => str_ends_with($file->getFilename(), '.conf'));

    $vhosts = $files->map(function ($file) {
        $content = File::get($file->getRealPath());

        // Pega o ServerName
        $servername = null;
        if (preg_match('/ServerName\s+([^\s]+)/', $content, $match)) {
            $servername = $match[1];
        }

        return [
            'file'       => $file->getFilename(),
            'servername' => $servername ?? '(não definido)',
        ];
    });

    return view('admin.virtualhosts.index', compact('vhosts'));
}


    public function json($file)
    {
        $fullPath = $this->path . '/' . $file;

        if (!File::exists($fullPath)) {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }

        $content = File::get($fullPath);
        $parsed = $this->parseVhost($content);

        return response()->json([
            'file' => $file,
            'content' => $content,
            'vhost' => $parsed,
        ]);
    }

    public function create()
    {
        return view('admin.virtualhosts.create');
    }

 public function store(Request $request)
{
    $fileName = $request->input('filename') . '.conf';
    $fullPath = $this->path . '/' . $fileName;
    $content = $request->input('content');

    // Cria arquivo temporário no storage do Laravel
    $tmpFile = storage_path("app/tmp_$fileName");
    file_put_contents($tmpFile, $content);

    // Copia com sudo para /etc/apache2/sites-available/
    exec("sudo cp " . escapeshellarg($tmpFile) . " " . escapeshellarg($fullPath));

    // Habilita o site e recarrega o Apache
    exec("sudo a2ensite " . escapeshellarg($fileName));
    exec("sudo systemctl reload apache2");

    return redirect()->route('virtualhosts.index')
        ->with('success', "Virtual Host {$fileName} criado com sucesso!");
}


    public function edit($file)
    {
        $fullPath = $this->path . '/' . $file;
        if (!File::exists($fullPath)) {
            abort(404);
        }

        $content = File::get($fullPath);
       return view('admin.virtualhosts.edit', [
        'fileName' => $file,
        'content' => $content
    ]);
    }

    public function update(Request $request, $file)
    {
        $fullPath = $this->path . '/' . $file;
        $content = $request->input('content');

        // Salva usando sudo tee
        $tmpFile = storage_path("app/tmp_vhost.conf");
        file_put_contents($tmpFile, $content);

        exec("sudo cp " . escapeshellarg($tmpFile) . " " . escapeshellarg($fullPath));
        exec("sudo systemctl reload apache2");

        return redirect()->back()->with('success', 'Virtual Host atualizado com sucesso!');
    }


    private function parseVhost($content)
    {
        preg_match('/<VirtualHost\s+(.+)>/', $content, $matches);
        $serverName = null;
        $serverAlias = [];

        if (preg_match('/ServerName\s+([^\s]+)/', $content, $sn)) {
            $serverName = $sn[1];
        }

        if (preg_match_all('/ServerAlias\s+([^\s]+)/', $content, $sa)) {
            $serverAlias = $sa[1];
        }

        return [
            'VirtualHost' => $matches[1] ?? null,
            'ServerName'  => $serverName,
            'ServerAlias' => $serverAlias,
        ];
    }

        public function destroy($file)
    {
        $fullPath = $this->path . '/' . $file;

        if (File::exists($fullPath)) {
            // Desabilita antes de apagar
            exec("sudo a2dissite " . escapeshellarg($file));

            // Apaga usando sudo rm
            exec("sudo rm " . escapeshellarg($fullPath));

            // Reinicia Apache
            exec("sudo systemctl restart apache2");
        }

        return redirect()->route('virtualhosts.index')->with('success', "Arquivo $file excluído com sucesso!");
    }
}

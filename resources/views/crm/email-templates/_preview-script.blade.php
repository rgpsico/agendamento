{{-- Modal de preview do template selecionado --}}
<div class="modal fade" id="modalPreviewTemplate" tabindex="-1" style="z-index:1060">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Preview: <span id="previewNomeModal"></span></h5>
                    <small class="text-muted">Assunto: <span id="previewAssuntoModal"></span></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="previewFrameModal" style="width:100%; height:520px; border:none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const templates = @json($emailTemplates->keyBy('id'));

    const exemplos = {
        nome: 'João Silva',
        email: 'joao@exemplo.com',
        telefone: '(21) 99999-9999',
        empresa: 'Studio Pilates',
        interesse: 'Pilates',
    };

    function substituir(texto) {
        return texto
            .replace(/{nome}/g,      exemplos.nome)
            .replace(/{email}/g,     exemplos.email)
            .replace(/{telefone}/g,  exemplos.telefone)
            .replace(/{empresa}/g,   exemplos.empresa)
            .replace(/{interesse}/g, exemplos.interesse);
    }

    window.verTemplateDoSelect = function (selectEl) {
        const id = selectEl.value;
        if (!id) { alert('Selecione um template primeiro.'); return; }
        const tpl = templates[id];
        document.getElementById('previewNomeModal').textContent    = tpl.nome;
        document.getElementById('previewAssuntoModal').textContent = substituir(tpl.assunto);
        document.getElementById('previewFrameModal').srcdoc        = substituir(tpl.corpo);
        new bootstrap.Modal(document.getElementById('modalPreviewTemplate')).show();
    };

    window.confirmarEnvioTemplate = function (selectEl, destinatarios) {
        const id = selectEl.value;
        if (!id) { alert('Selecione um template antes de enviar.'); return false; }
        const tpl = templates[id];
        return confirm(
            'Confirmar envio?\n\n' +
            'Template: ' + tpl.nome + '\n' +
            'Assunto: ' + substituir(tpl.assunto) + '\n' +
            'Destinatários: ' + destinatarios + '\n\n' +
            'Deseja realmente enviar este e-mail?'
        );
    };
})();
</script>

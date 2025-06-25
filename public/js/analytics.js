(function(){
    function send(type, value){
        var empresaMeta = document.querySelector('meta[name="empresa-id"]');
        var empresaId = empresaMeta ? empresaMeta.content : null;

        fetch('/analytics/event', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({event_type: type, value: value, empresa_id: empresaId})
        });
    }

    document.addEventListener('DOMContentLoaded', function(){
        if(window.performance && performance.timing){
            var time = performance.timing.domContentLoadedEventEnd - performance.timing.navigationStart;
            send('page_load_time', time);
        }
        send('visit');
        document.querySelectorAll('[data-whatsapp-button]').forEach(function(btn){
            btn.addEventListener('click', function(){ send('whatsapp_click'); });
        });
    });
})();

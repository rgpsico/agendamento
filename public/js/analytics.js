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
        // Mede depois do load: dentro do DOMContentLoaded o domContentLoadedEventEnd ainda e 0 e o resultado vinha negativo
        window.addEventListener('load', function(){
            setTimeout(function(){
                var nav = window.performance && performance.getEntriesByType ? performance.getEntriesByType('navigation')[0] : null;
                var time = nav ? Math.round(nav.domContentLoadedEventEnd) : 0;
                if(time > 0 && time < 600000){ send('page_load_time', time); }
            }, 0);
        });
        send('visit');
        document.querySelectorAll('[data-whatsapp-button]').forEach(function(btn){
            btn.addEventListener('click', function(){ send('whatsapp_click'); });
        });
    });
})();

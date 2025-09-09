@if($numero)
<a href="https://wa.me/{{ $numero }}?text={{ $mensagem }}" 
   target="_blank" 
   class="whatsapp-chat-btn"
   title="Atendimento via WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
@endif

<style>
.whatsapp-chat-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #25d366;
    color: white;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    z-index: 9999;
    transition: all 0.3s ease;
}
.whatsapp-chat-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0,0,0,0.3);
}
</style>

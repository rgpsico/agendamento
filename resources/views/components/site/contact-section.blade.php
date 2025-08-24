<section id="contact" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 section-header">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">{{ $site->contato_titulo ?? 'Fale Conosco' }}</h2>
            <div class="section-divider max-w-md mx-auto"></div>
            <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                <p>
    {{ $site->contato_descricao ?? 'Estamos aqui para tirar suas dúvidas e ajudar você a começar sua jornada' }}
</p>

            </p>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="contact-info">
                    <div class="glass-card p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-purple-50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Informações de Contato</h3>
                        <div class="space-y-6">
                            @php
                                $endereco = $site->endereco->endereco ?? 'Av. Beira Mar, 1234<br>Praia do Sol - SP, 11000-000';
                                $telefone = $site->whatsapp ?? '(11) 99999-9999';
                                $email = $site->empresa->user->email ?? 'contato@oceanwave.com.br';
                                $horario = $site->horario ?? 'Seg - Sáb: 7h às 18h<br>Dom: 8h às 16h';
                            @endphp

                            <x-site.contact-info-item icon="fa-map-marker-alt" title="Endereço" :value="$endereco" />
                            <x-site.contact-info-item icon="fa-phone" title="Telefone" :value="$telefone" />
                            <x-site.contact-info-item icon="fa-envelope" title="Email" :value="$email" />
                            <x-site.contact-info-item icon="fa-clock" title="Horário" :value="$horario" />
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form">
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Nome</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Telefone</label>
                                <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Serviço de Interesse</label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                @foreach($site->servicos ?? [] as $servico)
                                    <option>{{ $servico->titulo }}</option>
                                @endforeach
                                <option>Informações Gerais</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Mensagem</label>
                            <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300" placeholder="Conte-nos mais sobre seu interesse..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full btn-primary text-white font-semibold py-4 rounded-lg hover:scale-105 transition-all duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

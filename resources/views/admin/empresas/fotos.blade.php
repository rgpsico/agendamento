<x-admin.layout title="Galeria de Fotos">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <x-header.titulo pageTitle="Galeria de Fotos" />

            <x-alert />

            <!-- Upload Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Adicionar Imagens</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('empresa.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <input type="hidden" name="empresa_id" value="{{ Auth::user()->empresa->id }}">
                        <div class="mb-3">
                            <label for="image" class="form-label">Escolha até 5 imagens</label>
                            <input type="file" name="image[]" id="image" class="form-control" multiple accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>

            <!-- Galeria -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Imagens Cadastradas</h4>
                </div>
                <div class="card-body">
                    <div class="galeria-grid" id="galeriaGrid">
                        @forelse ($model as $foto)
                            <div class="galeria-item" data-id="{{ $foto->id }}">
                                <img src="{{ asset('galeria_escola/' . $foto->image) }}" alt="Foto" class="img-fluid rounded shadow-sm">
                                <button class="btn-excluir" title="Excluir">
                                    &times;
                                </button>
                            </div>
                        @empty
                            <p>Nenhuma imagem cadastrada.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Fade-in e escala ao carregar
            gsap.from(".galeria-item", {
                duration: 0.6,
                opacity: 0,
                scale: 0.8,
                stagger: 0.1,
                ease: "power2.out"
            });

            // Hover animação
            const items = document.querySelectorAll('.galeria-item img');
            items.forEach(item => {
                item.addEventListener('mouseenter', () => gsap.to(item, { scale: 1.05, duration: 0.3 }));
                item.addEventListener('mouseleave', () => gsap.to(item, { scale: 1, duration: 0.3 }));
            });

            // Exclusão via AJAX
            const excluirBtns = document.querySelectorAll('.btn-excluir');
            excluirBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const item = this.closest('.galeria-item');
                    const fotoId = item.getAttribute('data-id');
                        ///{id}/excluirImagens
                    if(confirm('Deseja realmente excluir esta imagem?')) {
                         fetch(`/api/gallery/${fotoId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ id: fotoId })
                        }).then(res => res.json())
                          .then(data => {
                              if(data.success) {
                                  // animação de saída
                                  gsap.to(item, { opacity: 0, scale: 0, duration: 0.5, onComplete: () => item.remove() });
                              } else {
                                  alert('Erro ao excluir imagem.');
                              }
                          });
                    }
                });
            });
        });
    </script>

    <style>
        .galeria-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .galeria-item {
            position: relative;
        }
        .galeria-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .btn-excluir {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255,0,0,0.8);
            border: none;
            color: white;
            font-size: 18px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .galeria-item:hover .btn-excluir {
            opacity: 1;
        }
    </style>
</x-admin.layout>

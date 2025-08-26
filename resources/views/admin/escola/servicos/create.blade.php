<x-admin.layout title="{{$pageTitle}}">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <div class="page-header gsap-header">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="page-title">{{$pageTitle}}</h3>
						<ul class="breadcrumb">
							<li class="breadcrumb-item">
                                <a href="">Admin</a></li>
							<li class="breadcrumb-item active">{{$pageTitle}}</li>
						</ul>
					</div>
				</div>
			</div>

            <div class="row">
				<div class="col-12">
					<!-- General -->
					<div class="card gsap-card">
						<div class="card-header">
							<h4 class="card-title">General</h4>
						</div>
						<div class="card-body">
							<div class="card-body">
                                <x-alert/>
								@if(isset($model))
								<form action="{{route('admin.servico.update',['id' =>$model->id])}}" method="POST" enctype="multipart/form-data">
								@else 
								<form action="{{route('admin.servico.store')}}" method="POST" enctype="multipart/form-data">
								@endif
								@csrf
								
								@include('admin.escola.servicos._partials.form')  
								
								@if(isset($model))
									<div class="card-footer d-flex">
										<button class="btn btn-success gsap-btn">Atualizar</button>
								@else 
									<div class="card-footer d-flex">
										<button class="btn btn-success gsap-btn">Salvar</button>
								@endif
								</div>
							</div>
						</div>
					</div>
					<!-- /General -->
				</div>
			</div>
        </div>			
    </div>
    <!-- /Page Wrapper -->

    <!-- Import GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Header
            gsap.from(".gsap-header", {
                duration: 1,
                y: -50,
                opacity: 0,
                ease: "power3.out"
            });

            // Card
            gsap.from(".gsap-card", {
                duration: 1,
                y: 100,
                opacity: 0,
                ease: "power2.out"
            });

            // Botões
            gsap.from(".gsap-btn", {
                duration: 1,
                scale: 0.8,
                opacity: 0,
                ease: "bounce.out",
                delay: 0.5
            });
        });
    </script>
</x-admin.layout>

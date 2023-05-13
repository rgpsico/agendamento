<div class="tab-pane fade show active" id="per_details_tab">
                        
    <!-- Personal Details -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between">
                        <span>Sobre</span> 
                        <a class="edit-link" data-bs-toggle="modal" href="#edit_personal_details"><i class="fa fa-edit me-1"></i>Edit</a>
                    </h5>
                    <div class="row">
                        <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Nome</p>
                        <p class="col-sm-10">{{$model->nome ?? 'Arpoador Surf Club'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Data de Cadastro</p>
                        <p class="col-sm-10">{{$model->data_created ?? '17/12/2022'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">E-mail</p>
                        <p class="col-sm-10">{{$model->email ?? 'gerrojj@12.com'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Celular</p>
                        <p class="col-sm-10">{{$model->telefone ?? '21 990271287'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-sm-2 text-muted text-sm-end mb-0">Endereço</p>
                        <p class="col-sm-10 mb-0">{{$model->endereco->rua ?? 'Rua Saint roman'}}<br>
                        {{$model->endereco->uf ?? 'Rj'}},<br>
                        {{$model->endereco->cidade ?? 'Rio de Janeiro'}} - {{$model->endereco->numero ?? '200'}},<br>
                        {{$model->endereco->nacionalidade ?? 'Brasil'}}.</p>
                    </div>
                </div>
            </div>
            
          
            
            
        </div>

    
    </div>
    <!-- /Personal Details -->

</div>
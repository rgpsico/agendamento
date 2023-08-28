@extends('alunoadmin::layouts.master')

@section('content')

<style>
    body {
    background-color: #fff;
    min-height: 100vh;
    font: normal 16px sans-serif;
    padding: 40px 0;
}

.container.gallery-container {
    background-color: #fff;
    color: #35373a;
    min-height: 100vh;
    padding: 30px 50px;
}

.gallery-container h1 {
    text-align: center;
    margin-top: 50px;
    font-family: 'Droid Sans', sans-serif;
    font-weight: bold;
}

.gallery-container p.page-description {
    text-align: center;
    margin: 25px auto;
    font-size: 18px;
    color: #999;
}

.tz-gallery {
    padding: 40px;
}

/* Override bootstrap column paddings */
.tz-gallery .row > div {
    padding: 2px;
}

.tz-gallery .lightbox img {
    width: 100%;
    border-radius: 0;
    position: relative;
}

.tz-gallery .lightbox:before {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -13px;
    margin-left: -13px;
    opacity: 0;
    color: #fff;
    font-size: 26px;
    font-family: 'Glyphicons Halflings';
    content: '\e003';
    pointer-events: none;
    z-index: 9000;
    transition: 0.4s;
}


.tz-gallery .lightbox:after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
 
    opacity: 0;
    background-color: rgba(46, 132, 206, 0.7);
    content: '';
    transition: 0.4s;
}

.tz-gallery .lightbox:hover:after,
.tz-gallery .lightbox:hover:before {
    opacity: 1;
}

.baguetteBox-button {
    background-color: transparent !important;
}

@media(max-width: 768px) {
    body {
        padding: 0;
    }
}
</style>
<div class="page-wrapper" style="min-height: 239px;">
    <div class="content container-fluid">
        <!-- Page Header -->
        <x-breadcrumb-aluno title="{{$title}}"/>
       
        <div class="row">
            
            <form action="{{route('aluno.upload')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="usuario_id" value="{{Auth::user()->aluno->id}}">
                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="file" class="form-control" name="image[]" multiple required>        </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-success">Enviar</button>
                    </div>
                </div>                        
             
            </form>
    <div class="container gallery-container">
    <h1>Fotos</h1>
    <p class="page-description text-center"></p>
    <div class="tz-gallery">
        <x-alert/>
        <div class="row">
            @foreach ($model as $value )
            <div class="col-sm-12 col-md-4">
                <a class="lightbox" href="{{asset('aluno_galeria/'.$value->image)}}">
                    <img src="{{asset('aluno_galeria/'.$value->image)}}" alt="Bridge">
                </a>
            </div>
        @endforeach
       </div>
    </div>

</div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>
<script>
    baguetteBox.run('.tz-gallery');
</script>
        </div>   
@endsection

@php 
$img  = 'admin/img/surfbread2.png';
@endphp
@isset($model)
   @php $img = ($model->banners == '') ? 'admin/img/surfbread2.png' : 'banner/'.$model->banners; @endphp
  
@endisset



<style>
  .breadcrumb-bar-two {
    background: url("{{ asset($img) }}") no-repeat;
  background-size: cover;
}
</style>



<div class="breadcrumb-bar-two" >
    <div class="container">
        <div class="row align-items-center inner-banner" style="height:400px;">
            <div class="col-md-12 col-12 text-center">
                <h2 class="breadcrumb-title">{{$title ?? 'Home'}}</h2>
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                    
                       
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
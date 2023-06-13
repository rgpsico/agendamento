<style>
  .breadcrumb-bar-two {
  background: url('{{asset('admin/img/surf.jpg')}}') no-repeat;
  background-size: cover;
}
</style>

<div class="breadcrumb-bar-two" >
    <div class="container">
        <div class="row align-items-center inner-banner" style="height:400px;">
            <div class="col-md-12 col-12 text-center">
                <h2 class="breadcrumb-title">{{$title ?? ''}}</h2>
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">{{$title ?? ''}} aaaa</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">{{$title ?? ''}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
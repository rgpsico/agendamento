@php 
$rating =  $model->avaliacao->avg('avaliacao');
@endphp
<div class="rating">
    @for ($i = 1; $i <= 5; $i++)
        @if (round($rating) >= $i)
            <i class="fas fa-star filled"></i>
        @else
            <i class="fas fa-star"></i>
        @endif
    @endfor
    <span class="d-inline-block average-rating">({{$model->avaliacao->count()}})</span>
</div>

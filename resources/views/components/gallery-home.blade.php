<div class="clinic-details">
    <p class="doc-location">
        {{$value->uf ?? ''}}, {{$value->cep ?? ''}}</p>
      
      
    <ul class="clinic-gallery">
        <li>
            <a href="{{asset('template/assets/img/features/feature-01.jpg')}}" data-fancybox="gallery">
                <img src="{{asset('template/assets/img/features/feature-01.jpg')}}" alt="Feature">
            </a>
        </li>
        <li>
            <a href="{{asset('template/assets/img/features/feature-02.jpg')}}" data-fancybox="gallery">
                <img  src="{{asset('template/assets/img/features/feature-02.jpg')}}" alt="Feature">
            </a>
        </li>
        <li>
            <a href="{{asset('template/assets/img/features/feature-03.jpg')}}" data-fancybox="gallery">
                <img src="{{asset('template/assets/img/features/feature-03.jpg')}}" alt="Feature">
            </a>
        </li>
        <li>
            <a href="{{asset('template/assets/img/features/feature-04.jpg')}}" data-fancybox="gallery">
                <img src="{{asset('template/assets/img/features/feature-04.jpg')}}" alt="Feature">
            </a>
        </li>
    </ul>
</div>
{{-- <div class="clinic-services">
    <span>Dental Fillings</span>
    <span> Whitneing</span>
</div> --}}
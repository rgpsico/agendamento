<div class="clinic-details">
    <p class="doc-location">
        {{$value->endereco->uf ?? ''}}, {{$value->endereco->cep ?? ''}}</p>
      
     

    <ul class="clinic-gallery">
        @foreach ($model->galeria as $gal)
        <li>
            <a href="{{ asset('galeria_escola/' . $gal->image) }}" data-fancybox="gallery">
                <img src="{{ asset('galeria_escola/' . $gal->image) }}" alt="Feature" class="img-fluid">
            </a>
        </li>
        @endforeach  
    </ul>
</div>
{{-- <div class="clinic-services">
    <span>Dental Fillings</span>
    <span> Whitneing</span>
</div> --}}
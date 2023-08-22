<!-- resources/views/components/data-teste.blade.php -->

<table class="datatable table table-hover table-center mb-0">
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
      
        @isset($model )
            
       
        @foreach ($model as $item)
        <tr>
            @foreach ($headers as $header)
            <td>{{ $item->$header }}</td>
            @endforeach
    
            @if(count($actions))
            <td>
                @if(array_key_exists('edit', $actions))
                <a  class='btn btn-warning' href="{{ route($actions['edit'], $item->id) }}">Editar</a>
                @endif
            </td>   
            <td> 
                @if(array_key_exists('delete', $actions))
                <form action="{{ route($actions['delete'], $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Excluir</button>
                </form>
                @endif
            </td>

            <td> 
                @if(array_key_exists('ver', $actions))
                    <a  class='btn btn-info' href="{{ route($actions['ver'], $item->id) }}">Ver</a>
                @endif
            </td>
            @endif
        </tr>
    @endforeach
    @endisset
    
    </tbody>
</table>

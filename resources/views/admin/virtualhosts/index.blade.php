<x-admin.layout title="Virtual Hosts">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="page-title">Virtual Hosts</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Arquivo</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($files as $file)
                                            <tr class="linha_{{ $loop->index }}">
                                                <td>{{ $file->getFilename() }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm bg-danger-light bt_excluir" data-file="{{ $file->getFilename() }}">
                                                        <i class="fe fe-trash"></i> Excluir
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- GSAP -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>

<script>
$(document).ready(function() {
  

    $(document).on("click", ".bt_excluir", function() {
        let file = $(this).data('file');
        let row = $(this).closest('tr');

        if(confirm('Deseja realmente excluir o Virtual Host "' + file + '"?')) {
            $.ajax({
                url: '{{ route("virtualhosts.destroy", ":file") }}'.replace(':file', file),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if(res.success) {
                        // animação de saída com GSAP
                        gsap.to(row, {opacity:0, y:-20, duration:0.5, onComplete:()=>row.remove()});
                    }
                }
            });
        }
    });

    // animação de entrada das linhas
    $('tbody tr').each(function(i){
        gsap.from($(this), {opacity:0, y:20, duration:0.5, delay:i*0.05});
    });
});
</script>
</x-admin.layout>

<x-admin.layout title="DashBoard">
    <style>
   .date-filter .form-control {
    width: 40%;
    margin:5px;
}

#filter-button {
    width: 15%;
}

.btn{
    margin-top:5px;
    height: 40px;    
} 

    </style>
    <div class="page-wrapper">
        <div class="content container-fluid">
        
          @include('admin.empresas._partials.header')
            @include('admin.empresas._partials.modal')
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary border-primary">
                                    <i class="fe fe-users"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{$numeroTotalDeAlunos}}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total de Alunos</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-credit-card"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{$arrecadacao}}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                
                                <h6 class="text-muted">Total Arrecadação</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-danger border-danger">
                                    <i class="fe fe-money"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{$numero_total_de_aulas}}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                
                                <h6 class="text-muted">N Aulas</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-danger border-danger">
                                    <i class="fe fe-money"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{$aulasCanceladas}}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                
                                <h6 class="text-muted">Aulas canceladas</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-warning border-warning">
                                    <i class="fe fe-money"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>R$ {{$arrecadacaoUltimos30Dias}}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                
                                <h6 class="text-muted">Arrecadação Mensal</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-6">
                
                    <!-- Sales Chart -->
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Arrecadação</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    <!-- /Sales Chart -->
                    
                </div>
                <div class="col-md-12 col-lg-6">
                
                    <!-- Invoice Chart -->
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Alunos</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    <!-- /Invoice Chart -->
                    
                </div>	
            </div>
            
            
        </div>			
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>


        var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [01,02,04],  // Este é o array de datas
            datasets: [{
                label: 'Arrecadação',
                data: [10,20,20],  // Este é o array de valores de arrecadação
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    var cancelados  = <?php echo json_encode($aulasCanceladas); ?>;
    var realizadas = <?php echo json_encode($realizadas); ?>;
  
    var ctx2 = document.getElementById('statusChart').getContext('2d');

   
    var statusChart = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['Aulas Feitas', 'Aulas Canceladas'],  // Este é o array de labels
        datasets: [{
            data: [realizadas, cancelados],  // Este é o array de valores correspondentes
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

    </script>
    <!-- /Page Wrapper -->
</x-layoutsadmin>
<x-admin.layout title="Listar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           <x-header.titulo pageTitle="{{$pageTitle}}" />
            <!-- /Page Header -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary border-primary">
                                    <i class="fe fe-users"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>168</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Doctors</h6>
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
                                    <h3>487</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                
                                <h6 class="text-muted">Patients</h6>
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
                                    <h3>485</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                
                                <h6 class="text-muted">Appointment</h6>
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
                                    <i class="fe fe-folder"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>$62523</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                
                                <h6 class="text-muted">Revenue</h6>
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
                            <h4 class="card-title">Revenue</h4>
                        </div>
                        <div class="card-body">
                            <div id="morrisArea" style="position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg height="342" version="1.1" width="494.5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.703125px; top: -0.25px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.2.0</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><text x="29.623046875" y="306" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,306H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="235.75" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">75</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,235.75H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="165.5" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">150</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,165.5H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="95.25" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">225</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,95.25H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="25" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">300</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,25H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="469.5" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2019</tspan></text><text x="398.30301785001143" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2018</tspan></text><text x="327.10603570002286" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2017</tspan></text><text x="255.71399332496577" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2016</tspan></text><text x="184.5170111749772" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2015</tspan></text><text x="113.3200290249886" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2014</tspan></text><text x="42.123046875" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2013</tspan></text><path fill="#346fa2" stroke="none" d="M42.123046875,249.8C59.92229241249715,240.43333333333334,95.52078348749146,233.4083333333333,113.3200290249886,212.33333333333331C131.11927456248574,191.25833333333333,166.71776563748006,83.54166666666669,184.5170111749772,81.20000000000002C202.31625671247434,78.85833333333336,237.91474778746863,174.89229366165074,255.71399332496577,193.60000000000002C273.56200391873006,212.3589603283174,309.2580251062586,228.721796625627,327.10603570002286,231.06666666666666C344.90528123752,233.40512995896032,380.5037723125143,238.09166666666664,398.30301785001143,212.33333333333331C416.1022633875086,186.575,451.70075446250286,71.83333333333333,469.5,25L469.5,306L42.123046875,306Z" fill-opacity="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 0.5;"></path><path fill="none" stroke="#1b5a90" d="M42.123046875,249.8C59.92229241249715,240.43333333333334,95.52078348749146,233.4083333333333,113.3200290249886,212.33333333333331C131.11927456248574,191.25833333333333,166.71776563748006,83.54166666666669,184.5170111749772,81.20000000000002C202.31625671247434,78.85833333333336,237.91474778746863,174.89229366165074,255.71399332496577,193.60000000000002C273.56200391873006,212.3589603283174,309.2580251062586,228.721796625627,327.10603570002286,231.06666666666666C344.90528123752,233.40512995896032,380.5037723125143,238.09166666666664,398.30301785001143,212.33333333333331C416.1022633875086,186.575,451.70075446250286,71.83333333333333,469.5,25" stroke-width="2" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><circle cx="42.123046875" cy="249.8" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="113.3200290249886" cy="212.33333333333331" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="184.5170111749772" cy="81.20000000000002" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="255.71399332496577" cy="193.60000000000002" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="327.10603570002286" cy="231.06666666666666" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="398.30301785001143" cy="212.33333333333331" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="469.5" cy="25" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle></svg><div class="morris-hover morris-default-style" style="left: 285.075px; top: 161px; display: none;"><div class="morris-hover-row-label">2017</div><div class="morris-hover-point" style="color: #1b5a90">
Revenue:
80
</div></div></div>
                        </div>
                    </div>
                    <!-- /Sales Chart -->
                    
                </div>
                <div class="col-md-12 col-lg-6">
                
                    <!-- Invoice Chart -->
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Status</h4>
                        </div>
                        <div class="card-body">
                            <div id="morrisLine" style="position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg height="342" version="1.1" width="494.5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.84375px; top: -0.25px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.2.0</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><text x="29.623046875" y="306" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,306H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="235.75" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">50</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,235.75H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="165.5" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">100</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,165.5H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="95.25" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">150</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,95.25H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="29.623046875" y="25" text-anchor="end" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">200</tspan></text><path fill="none" stroke="#aaaaaa" d="M42.123046875,25H469.5" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="469.5" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2019</tspan></text><text x="362.7288926142197" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2018</tspan></text><text x="255.95778522843943" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2017</tspan></text><text x="148.89415426078028" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2016</tspan></text><text x="42.123046875" y="318.5" text-anchor="middle" font-family="sans-serif" font-size="10px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 10px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,5.5)"><tspan dy="3.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2015</tspan></text><path fill="none" stroke="#ff9d00" d="M42.123046875,263.85C68.81582372144507,253.3125,122.20137741433521,237.4846272229822,148.89415426078028,221.7C175.66006200269507,205.87212722298221,229.19187748652465,140.91730506155952,255.95778522843943,137.4C282.65056207488453,133.8923050615595,336.0361157677746,198.86875,362.7288926142197,193.6C389.4216694606648,188.33124999999998,442.8072231535549,119.8375,469.5,95.25" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#1b5a90" d="M42.123046875,165.5C68.81582372144507,193.6,122.20137741433521,276.1461525307797,148.89415426078028,277.9C175.66006200269507,279.6586525307797,229.19187748652465,184.82595759233928,255.95778522843943,179.55C282.65056207488453,174.28845759233928,336.0361157677746,241.01875,362.7288926142197,235.75C389.4216694606648,230.48125,442.8072231535549,161.9875,469.5,137.4" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><circle cx="42.123046875" cy="263.85" r="4" fill="#ff9d00" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="148.89415426078028" cy="221.7" r="4" fill="#ff9d00" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="255.95778522843943" cy="137.4" r="4" fill="#ff9d00" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="362.7288926142197" cy="193.6" r="4" fill="#ff9d00" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="469.5" cy="95.25" r="4" fill="#ff9d00" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="42.123046875" cy="165.5" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="148.89415426078028" cy="277.9" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="255.95778522843943" cy="179.55" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="362.7288926142197" cy="235.75" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="469.5" cy="137.4" r="4" fill="#1b5a90" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle></svg><div class="morris-hover morris-default-style" style="left: 212.927px; top: 49px; display: none;"><div class="morris-hover-row-label">2017</div><div class="morris-hover-point" style="color: #1b5a90">
                            Doctors:
                            90
</div>
<div class="morris-hover-point" style="color: #ff9d00">
Patients:
120
</div></div></div>
                        </div>
                    </div>
                    <!-- /Invoice Chart -->
                    
                </div>	
            </div>
            
            
        </div>			
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>
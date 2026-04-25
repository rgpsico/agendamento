<div class="d-flex gap-2 flex-wrap mb-4">
    <a class="btn btn-sm {{ request()->routeIs('crm.dashboard') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('crm.dashboard') }}">Dashboard</a>
    <a class="btn btn-sm {{ request()->routeIs('crm.pipeline.*') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('crm.pipeline.index') }}">Pipeline</a>
    <a class="btn btn-sm {{ request()->routeIs('crm.leads.*') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('crm.leads.index') }}">Leads</a>
    <a class="btn btn-sm {{ request()->routeIs('crm.campanhas.*') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('crm.campanhas.index') }}">Campanhas</a>
    <a class="btn btn-sm {{ request()->routeIs('crm.formularios.*') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('crm.formularios.index') }}">Formularios</a>
    <a class="btn btn-sm {{ request()->routeIs('crm.relatorios.*') ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('crm.relatorios.index') }}">Relatorios</a>
</div>

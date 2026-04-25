<x-admin.layout title="CRM - Novo Lead">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header"><h3 class="page-title">Novo Lead</h3></div>
            @include('crm._nav')
            <x-alert-messages />
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('crm.leads.store') }}">
                        @include('crm.leads._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>

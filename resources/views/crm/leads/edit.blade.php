<x-admin.layout title="CRM - Editar Lead">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header"><h3 class="page-title">Editar Lead</h3></div>
            @include('crm._nav')
            <x-alert-messages />
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('crm.leads.update', $lead) }}">
                        @method('PUT')
                        @include('crm.leads._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>

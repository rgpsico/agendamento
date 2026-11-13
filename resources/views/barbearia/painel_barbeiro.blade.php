@extends('barbearia.layout')
@section('titulo', 'Meu painel')
@section('conteudo')
    <h1>Olá, {{ auth()->user()->nome }}</h1>
    <div class="aviso info">A sua fila de clientes e a agenda do dia chegam na próxima etapa.</div>
@endsection

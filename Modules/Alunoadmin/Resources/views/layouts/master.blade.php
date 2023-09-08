<!DOCTYPE html>
<html lang="pt-br">   
    {{-- Laravel Vite - JS File --}}
      {{-- {{ module_vite('build-alunoadmin', 'Resources/assets/js/app.js') }}  --}}
      <script src="{{asset('admin/js/jquery-3.6.3.min.js')}}"></script>

<x-adminaluno.header title='teste'/>
    <body>
       @yield('content')
        
    </body>
</html>

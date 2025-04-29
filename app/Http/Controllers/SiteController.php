<?php

namespace App\Http\Controllers;

use App\Models\Alunos;
use App\Models\EmpresaSite;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SiteController extends Controller
{


    public function mostrar($slug)
    {
        dd($slug);
        $site = EmpresaSite::where('slug', $slug)
            ->with(['servicos', 'depoimentos', 'contatos'])
            ->firstOrFail();

        return view('site.publico', compact('site'));
    }
}

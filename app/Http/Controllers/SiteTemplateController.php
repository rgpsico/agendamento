<?php

namespace App\Http\Controllers;

use App\Models\SiteTemplate;
use Illuminate\Http\Request;

class SiteTemplateController extends Controller
{
    public function index()
{
 
    // Pega todos os templates
    $templates = SiteTemplate::all();

    // Passa para a view usando 'model' para seguir o mesmo padrão do admin
    return view('admin.sitetemplate.index', [
        'model' => $templates,
        'pageTitle' => 'Templates de Site',
        'route' => route('site-templates.create')
    ]);
}

    public function create()
    {
        return view('admin.sitetemplate.create',[
           'pageTitle' => 'Criar Template de Site'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:site_templates,slug',
            'descricao' => 'nullable|string',
            'path_view' => 'required|string|max:255',
            'preview_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $request->file('preview_image')->store('templates', 'public');
        }

        SiteTemplate::create($data);

        return redirect()->route('site-templates.index')->with('success', 'Template criado com sucesso!');
    }

   public function edit(SiteTemplate $siteTemplate)
{
    $pageTitle = 'Editar Template de Site';
    $model = $siteTemplate; // renomeia
    return view('admin.sitetemplate.edit', compact('model', 'pageTitle'));
}


    public function update(Request $request, SiteTemplate $siteTemplate)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:site_templates,slug,' . $siteTemplate->id,
            'descricao' => 'nullable|string',
            'path_view' => 'required|string|max:255',
            'preview_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $request->file('preview_image')->store('templates', 'public');
        }

        $siteTemplate->update($data);

        // Redireciona para o edit do mesmo template
        return redirect()
            ->route('site-templates.edit', $siteTemplate->id)
            ->with('success', 'Template atualizado com sucesso!');
    }


    public function destroy(SiteTemplate $siteTemplate)
    {
        $siteTemplate->delete();
        return redirect()->route('admin.sitetemplate.index')->with('success', 'Template removido!');
    }
}

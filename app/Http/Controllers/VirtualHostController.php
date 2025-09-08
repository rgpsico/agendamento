<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VirtualHostController extends Controller
{
    protected $path = '/etc/apache2/sites-available';


  public function index()
    {
        // Garante que a pasta existe
        if (!File::exists($this->path)) {
            return view('admin.virtualhosts.index', ['files' => []]);
        }

        // Lista somente arquivos .conf
        $files = collect(File::files($this->path))
                    ->filter(fn($file) => str_ends_with($file->getFilename(), '.conf'));

        return view('admin.virtualhosts.index', compact('files'));
    }
    public function json($file)
    {
        $fullPath = $this->path . '/' . $file;

        if (!File::exists($fullPath)) {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }

        $content = File::get($fullPath);
        $parsed = $this->parseVhost($content);

        return response()->json([
            'file' => $file,
            'content' => $content,
            'vhost' => $parsed,
        ]);
    }

public function create()
{
    return view('admin.virtualhosts.create');
}

public function edit($file)
{
    $fullPath = $this->path . '/' . $file;
    if (!File::exists($fullPath)) {
        abort(404);
    }
    $content = File::get($fullPath);
    return view('admin.virtualhosts.edit', compact('file', 'content'));
}

public function update(Request $request, $file)
{
    $fullPath = $this->path . '/' . $file;
    File::put($fullPath, $request->input('content'));
    exec("sudo systemctl reload apache2");
    return redirect()->route('admin,virtualhosts.index')->with('success', 'Virtual Host atualizado com sucesso!');
}


}

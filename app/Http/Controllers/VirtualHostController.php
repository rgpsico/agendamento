<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VirtualHostController extends Controller
{
    protected $path = '/etc/apache2/sites-available';

    public function index()
{
    $files = File::files($this->path);

    $vhosts = collect($files)->map(function($file) {
        $content = File::get($file->getPathname());
        preg_match('/ServerName\s+(.+)/', $content, $matches);
        return [
            'file' => $file->getFilename(),
            'servername' => $matches[1] ?? 'N/A',
        ];
    });

    return view('admin.virtualhosts.index', compact('vhosts'));
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

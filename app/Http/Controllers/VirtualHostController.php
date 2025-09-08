<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VirtualHostController extends Controller
{
    protected $path = '/etc/apache2/sites-available';

    public function index()
    {
        // Lista todos os arquivos .conf
        $files = File::files($this->path);

        return view('admin.virtualhosts.index', compact('files'));
    }

    public function destroy($file)
    {
        $fullPath = $this->path . '/' . $file;

        if (File::exists($fullPath)) {
            // Desabilita antes de apagar
            exec("sudo a2dissite " . escapeshellarg($file));

            // Apaga o arquivo
            File::delete($fullPath);

            // Reinicia Apache
            exec("sudo systemctl restart apache2");
        }

        return redirect()->route('virtualhosts.index')->with('success', "Arquivo $file excluído com sucesso!");
    }
}

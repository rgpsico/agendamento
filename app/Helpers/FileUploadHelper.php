<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class FileUploadHelper
{
    /**
     * Faz upload de um arquivo de um request e retorna o nome do arquivo.
     *
     * @param Request $request
     * @param string $fieldName  Nome do campo no request
     * @param string $folder     Pasta para salvar o arquivo
     * @return string|null       Nome do arquivo salvo ou null se não houver arquivo
     */
    public static function uploadFile(Request $request, string $fieldName, string $folder): ?string
    {
        if ($request->hasFile($fieldName)) {
            /** @var UploadedFile $file */
            $file = $request->file($fieldName);
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($folder), $filename);
            return $filename;
        }

        return null;
    }
}

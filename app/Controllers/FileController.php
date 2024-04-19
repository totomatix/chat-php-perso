<?php

namespace App\Controllers;

use App\Models\FileModel;

use App\Controllers\BaseController;


class FileController extends BaseController
{
    //Récupére le fichier
    public function display($id)
    {
        $FileModel = new FileModel();

        $folder1 = $FileModel->getDirectory($id);
        $filename = $FileModel->getFileName($id);
        $fullpath  =  WRITEPATH . 'chatFiles/' . $folder1->directory . '/' . $folder1->directory . '_' . $filename->name;

        helper("filesystem");

        $file = new \CodeIgniter\Files\File($fullpath, true);

        $binary = readfile($fullpath);

        return $this->response
            ->setHeader('Content-Type', $file->getMimeType())
            ->setHeader('filename', $file->getBasename())
            ->setHeader('path', $fullpath)
            ->setStatusCode(200)
            ->setBody($binary);
    }
}

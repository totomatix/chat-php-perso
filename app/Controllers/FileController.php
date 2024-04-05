<?php

namespace App\Controllers;

use App\Models\FileModel;

use App\Controllers\BaseController;
use CodeIgniter\CLI\Console;


class FileController extends BaseController
{
    //Récupére le fichier est le return sous forme binaire
    public function display($id)
    {
        $FileModel = new FileModel();

        $folder1 = $FileModel->getDirectory($id);
        $filename = $FileModel->getFileName($id);
        // Construit le chemin complet menant au fichier
        $fullpath  =  WRITEPATH . 'chatFiles/' . $folder1->directory . '/' . $folder1->directory . '_' . $filename->name;

        // Charge le helper "filesystem" qui aide au travail avec des fichier et des répéretoires
        helper("filesystem");

        // Créé le fichier via le chemin désiré avec l'aide du helper
        $file = new \CodeIgniter\Files\File($fullpath, true);

        // Lit le fichier et le donne à la réponse sous forme binaire
        $binary = readfile($fullpath);

        // Rajoute les header qu'il faut et return la réponse
        return $this->response
            ->setHeader('Content-Type', $file->getMimeType())
            ->setHeader('filename', $file->getBasename())
            ->setHeader('path', $fullpath)
            ->setStatusCode(200)
            ->setBody($binary);
    }
}

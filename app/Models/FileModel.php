<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    //table auquel le model est rataché
    protected $table = 'file';
    //clé primaire de la table
    protected $primaryKey = 'id';
    //champ de la table
    protected $allowedFields = ['id', 'name', 'created_at', 'directory'];

    public function getDirectory($id)
    {
        $FileModel = new FileModel();

        $directory = $FileModel
            ->select('directory')
            ->where('id', $id)
            ->get();
        
        $row = $directory->getRow();
        return $row;
    } 

    public function getFileName($id)
    {
        $FileModel = new FileModel();

        $fileName = $FileModel
            ->select('name')
            ->where('id', $id)
            ->get();

        $row = $fileName->getRow();
        return $row;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    //table auquel le model est rataché
    protected $table = 'users';
    //clé primaire de la table
    protected $primaryKey = 'id';
    //champ de la table
    protected $allowedFields = ['id', 'name', 'email', 'password'];
}

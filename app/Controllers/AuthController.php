<?php

namespace App\Controllers;

use CodeIgniter\CodeIgniter;
use App\Models\UsersModel;
use App\Libraries\Hash;

class AuthController extends BaseController
{

    public function __construct()
    {
        helper(['url', 'form']);
    }

    // Affiche la page de Log In
    public function index()
    {
        return $this->render('auth/login.twig');
    }

    // Affiche la page de création de compte
    public function register()
    {
        $data['base_url'] = base_url("/");
        return $this->render('auth/register.twig', $data);
    }

    // Sauvegarde lors de la création de compte
    public function save()
    {
        $validation = $this->validate([
            'name'=>'required',
            'email'=>'required|valid_email|is_unique[users.email]',
            'password'=>'required|min_length[4]',
            'cpassword'=>'required|min_length[4]|matches[password]'
        ]);

        if (!$validation) {
            $base_url = base_url("/public");
            $data = [
                'validation' => $this->validator,                
                'base_url' => $base_url,    
            ];
            return $this->render('auth/register.twig',$data);      
        } else {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $values = [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ];
            $usersModel = new UsersModel();
            $usersModel->insert($values);

            return redirect()->route('/');
        }
    }
    //Permet de check si les informations de la crétion de compte sont validées
    public function check()
    {
        $validation = \Config\Services::validation();
        $validation = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email est requis',
                    'valid_email' => 'Entrez un Email valide',
                    'is_not_unique' => 'L\'email n\'est pas enregistré'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required' => 'Le mot de pass est requis',
                    'min_length' => 'Le mot de pass doit contenir au moins 4 caractères'
                ]
            ]
        ]);

        if (!$validation) {
            $data['validation'] = $this->validator;
            return $this->render('auth/login.twig',$data);
        } else {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $usersModel = new UsersModel();
            $user_info = $usersModel->where('email', $email)->first();
            $check_password = Hash::check($password, $user_info['password']);

            if (!$check_password) {
                return $this->render('auth/login.twig',['validation'=>$this->validator]);
            } else {
                $session = \Config\Services::session();
                $user_info = $usersModel->where('email', $email)->first();
                $user_id = $user_info['id'];
                $newdata = [
                    'id'  => $user_id,
                    'logged_in' => true,
                ];
                $session->set($newdata);
                return redirect()->route('auth');
            }
        }
    }

    //Permet de se Log Out et de revenir à l'ecran de Log In
    function logOut()
    {
        $session = \Config\Services::session();
        if ($session->logged_in == true) {
            $session->destroy();
            return redirect()->route('/');
        }
    }
}

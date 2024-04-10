<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Libraries\Hash;

class AuthController extends BaseController
{
    protected $helpers = ['form'];

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
        $data = $this->request->getPost();
        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'name' => 'required',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[4]',
                'cpassword' => 'required|min_length[4]|matches[password]'
            ],
            [
                'name' => [
                    'required' => 'Le nom est requis'
                ],
                'email' => [
                    'required' => 'L\'email dois être renseigné',
                    'valid_email' => 'L\'email dois être valide',
                    'is_not_unique' => 'L\'email est déjà utilisé'
                ],
                'password' => [
                    'required' => 'Le mot de passe est requis',
                    'min_length' => 'Le mot de passe dois contenire au moins 4 caractéres'
                ],
                'cpassword' => [
                    'required' => 'La confirmatrion du mot de passe est requis',
                    'min_length' => 'La confirmatrion du mot de passe dois contenire au moins 4 caractéres',
                    'matches' => 'La confirmation du mot de pass dois correspondre avec le mot de passe'
                ]
            ]
        );

        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            $data = [
                'error' => $errors
            ];
            return $this->render('auth/register.twig', $data);
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

            $data = [
                'success' => 'Compte créé avec succes!'
            ];

            return $this->render('auth/login.twig', $data);
        }
    }
    //Permet de check si les informations du Log In sont valides
    public function check()
    {
        $data = $this->request->getPost();

        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'email' => 'required|valid_email|is_not_unique[users.email]',
                'password' => 'required|min_length[4]',
            ],
            [
                'email' => [
                    'required' => 'L\'email dois être renseigné',
                    'valid_email' => 'L\'email dois être valide',
                    'is_not_unique' => 'Aucun compte ne correspond à cet Email'
                ],
                'password' => [
                    'required' => 'Le mot de pass est requis',
                    'min_length' => 'Le mot de pass dois contenire au moins 4 caractéres'
                ]
            ]
        );
        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            $data = [
                'error' => $errors
            ];
            return $this->render('auth/login.twig', $data);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usersModel = new UsersModel();

        $user_info = $usersModel->where('email', $email)->first();
        $check_password = Hash::check($password, $user_info['password']);
        $user_info = $usersModel->where('email', $email)->first();

        $session = \Config\Services::session();
        $user_id = $user_info['id'];

        $newdata = [
            'id'  => $user_id,
            'logged_in' => true,
        ];

        $session->set($newdata);

        if (!$check_password) {
            return $this->render('auth/login.twig', ['validation' => $this->validator]);
        } else {

            return redirect()->route('chat');
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

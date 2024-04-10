<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\MessageModel;
use App\Models\FileModel;
use CodeIgniter\CLI\Console;
use CodeIgniter\CodeIgniter;


class HomeController extends FileController
{

    // Affiche la page de chat avec les informations de l'utilisateurs connecté
    public function index()
    {
        $usersModel = new UsersModel();

        $loggedUserID = $_SESSION['id'];
        $userInfo = $usersModel->find($loggedUserID);

        $user = $usersModel->find();
        $data = [
            'CI_VERSION' => CodeIgniter::CI_VERSION,
            'ENVIRONMENT' => ENVIRONMENT,
            'userInfo' => $userInfo,
            'users' => $user
        ];
        return $this->render('chat/chat.twig', $data);
    }

    // Affiche la page de chat privé avec l'utilisateur choisit
    public function chatPerso()
    {
        $usersModel = new UsersModel();

        $base_url = base_url("/");

        $loggedUserID = $_SESSION['id'];
        $userInfo = $usersModel->find($loggedUserID);

        $user = $usersModel->find();

        $data = [
            'userInfo' => $userInfo,
            'users' => $user,
            'base_url' => $base_url,
        ];

        return $this->render('chat/chatPerso.twig', $data);
    }

    // Récupére les messages stocké en BDD selon le chat ou l'utilisateur se trouve 
    public function getMessage()
    {
        $messageModel = new MessageModel();

        if (!($_POST['idUser'] == null)) {
            $lastId = $_POST['lastId'];
            $idReceiveUser = $_POST['idUser'];
            $IdSendUser = $_SESSION['id'];

            $message = $messageModel->getMessages($lastId, $IdSendUser, $idReceiveUser);

            $messagesJson = json_encode($message);

            echo $messagesJson;
        } else {
            $IdSendUser = $_SESSION['id'];
            $lastId = $_POST['lastId'];
            $idReceiveUser = 0;

            $messages = $messageModel->getMessages($lastId, $IdSendUser, $idReceiveUser);

            $messagesJson = json_encode($messages);
            echo $messagesJson;
        }
    }

    // Upload le fichier selectionner par l'utilisateur dans le dossier writable/chatFile/{le mois - l'année}
    // Rajoute au nom du fichier {mois-anné_}
    public function fileUpload()
    {
        $data = array();

        if ($file = $this->request->getFile('file')) {
            if ($file->isValid() && !$file->hasMoved()) {
                // Récupére le nom du fichier et le remplace
                $name = $file->getName();
                $time = date("m-Y");

                $newName = $time . "_" . $name;

                // Stock le fichier
                $file->move('../writable/chatFiles/' . $time, $newName, true);
            }
        }
        return $this->response->setJSON($data);
    }

    // Post les message et les enregistre en BDD
    public function postMessage()
    {
        $messageModel = new MessageModel();
        $fileModel = new FileModel();

        // Condition pour verifier si le message est poster avec un fichier attaché
        if ($_POST['fileName'] != "") {

            $loggedUserID = $_SESSION['id'];
            $idReceiveUser = $_POST['idUser'];
            $message = $_POST['message'];
            $fileName = $_POST['fileName'];
            $directory = date("m-Y");

            // Enregistre en BDD le nom et le répertoir où est stocké le fichier
            $fileModel->save([
                'name' => $fileName,
                'directory' => $directory
            ]);

            // Récupére l'Id du fichier qui vient d'être enregistrer pour pouvoir le lier au message en BDD
            $idImage = $fileModel->insertId();

            // Effectue la fonction fileUpload()
            $this->fileUpload();

            // Sauvegarde le message en BDD
            $messageModel->save([
                'send_user_id' => $loggedUserID,
                'receive_user_id' => $idReceiveUser,
                'content' => $message,
                'id_image' => $idImage
            ]);
            // Si il n'y a pas de fichier attaché
        } else {

            $loggedUserID = $_SESSION['id'];
            $idReceiveUser = $_POST['idUser'];
            $message = $_POST['message'];

            $messageModel->save([
                'send_user_id' => $loggedUserID,
                'receive_user_id' => $idReceiveUser,
                'content' => $message
            ]);
        }
    }
}

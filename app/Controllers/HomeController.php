<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\MessageModel;
use App\Models\FileModel;
use CodeIgniter\CodeIgniter;


class HomeController extends BaseController
{

public function index(): string
    {
        $usersModel = new UsersModel();

        $loggedUserID = $_SESSION['id'];
        $userInfo = $usersModel->find($loggedUserID);

        $user = $usersModel->find();
        $data = [
            'CI_VERSION' => CodeIgniter::CI_VERSION,
            'ENVIRONMENT' => ENVIRONMENT,
            'userInfo'=>$userInfo,
            'users'=>$user
        ];
        return $this->render('chat/chat.twig', $data);
    }
public function chatPerso(): string
    {
        $usersModel = new UsersModel();

        $base_url = base_url("/");

        $loggedUserID = $_SESSION['id'];
        $userInfo = $usersModel->find($loggedUserID);

        $user = $usersModel->find();

        $data = [
            'userInfo'=>$userInfo,
            'users'=>$user,
            'base_url'=>$base_url
        ];

        return $this->render('chat/chatPerso.twig', $data);
    }
public function getMessage()
    {
    $messageModel = new MessageModel();

    if (!($_POST['idUser'] == null)) {
	$lastId = $_POST['lastId'];
    $idReceiveUser = $_POST['idUser'];
    $IdSendUser = $_SESSION['id'];

    $message = $messageModel->getMessages($lastId, $IdSendUser , $idReceiveUser);

    $messagesJson = json_encode($message);

    echo $messagesJson;
    }else{
    $IdSendUser = $_SESSION['id'];
    $lastId = $_POST['lastId'];
    $idReceiveUser = 0;

    $message = $messageModel->getMessages($lastId, $IdSendUser , $idReceiveUser);

    $messagesJson = json_encode($message);

    echo $messagesJson;
    }
}
public function postMessage()
    {
        $messageModel = new MessageModel();
        $fileModel = new FileModel();

        $loggedUserID = $_SESSION['id'];
        $idReceiveUser = $_POST['idUser'];
        $message = $_POST['message'];
        

        $messageModel->save([
            'send_user_id' => $loggedUserID,
            'receive_user_id' => $idReceiveUser,
            'content' => $message
        ]);
        if ($_POST['fileName'] != null) {

        $fileName = $_POST['fileName'];

        $fileModel->save([
            'name' => $fileName
        ]);
        }

    }
}

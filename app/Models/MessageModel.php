<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    //table auquel le model est rataché
    protected $table = 'message';
    //clé primaire de la table
    protected $primaryKey = 'id';
    //champ de la table
    protected $allowedFields = ['id', 'content', 'send_user_id', 'receive_user_id', 'created_at', 'id_image'];

    //Fonction de récupération des messages
    public function getMessages($lastId, $IdSendUser, $idReceiveUser)
    {
        $messageModel = new MessageModel();

        $where = "message.receive_user_id = $idReceiveUser and message.send_user_id = $IdSendUser and message.id > $lastId or message.receive_user_id = $IdSendUser and message.send_user_id = $idReceiveUser and message.id > $lastId";

        if ($idReceiveUser == 0) {
            $data = $messageModel
                ->asArray()
                ->select('message.id ,message.content,message.created_at,users.id as userId, users.name as userName,file.name as fileName,file.directory,file.id as idFile')
                ->join('users', 'message.send_user_id = users.id')
                ->join('file', 'message.id_image = file.id', 'left')
                ->orderBy('message.id', 'DESC')
                ->where('message.receive_user_id =', 0)
                ->where('message.id >', $lastId)
                ->find();
            return $data;
        } else {
            $data = $messageModel
                ->asArray()
                ->select('message.id,message.content,message.created_at,users.id as userId,users.name as userName,file.name as fileName,file.directory,file.id as idFile')
                ->join('users', 'message.send_user_id = users.id')
                ->join('file', 'message.id_image = file.id', 'left')
                ->orderBy('message.id', 'DESC')
                ->where($where)
                ->find();
            return $data;
        }
    }
}

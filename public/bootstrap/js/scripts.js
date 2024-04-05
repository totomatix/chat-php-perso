// definit lastId utilisé lors de la récupération des messages
let lastId = 0;

//Se produit lors du chargement de la page
window.onload = () => {
    //Verifie si l'élément avec l'Id "texte" est present et permet de savoir la nature du chat (general ou privé)
    if (document.querySelector("#texte") != null) {
        //Definit que la variable "texte" contient l'element qui a l'Id "texte"
        let texte = document.querySelector("#texte");
        //Ajoute une fonction qui se produit à chaque execution de l'evenement
        //L'evenement "keyup" s'execute quand la touche est préssée puis relachée
        texte.addEventListener("keyup", verifEntree);
        //chargeMessage();
        //Permet de definir une intervalle pour le lancement de la fonction 
        setInterval(chargeMessage, 1000);
        //Si aucun element a l'Id "texte"
    } else {
        let texte_perso = document.querySelector('#texte_perso');
        texte_perso.addEventListener("keyup", verifEntree);
        //chargeMessage();
        setInterval(chargeMessage, 1000);
    }

    if (document.querySelector('#valid') != null) {
        let valid = document.querySelector("#valid");
        //L'evenement "click" s'execute quand un click de la souris est effectué
        valid.addEventListener("click", ajoutMessage);
    } else {
        let valid_perso = document.querySelector('#valid_perso');
        valid_perso.addEventListener("click", ajoutMessage);
    }
}

//Fonction de charge des messages
function chargeMessage() {
    //Verifie si l'élément avec l'Id "texte" est present et permet de savoir que l'utilisateur est sur le chat general
    if (document.querySelector("#texte") != null) {
        //Si l'element est présent cela signifie que l'utilisateur se trouve sur le chat general et definit l'id du receveur à null
        var idUser = null;
        $(document).ready(function () {
            var messages = '';
            //Effectue la requête ajax
            $.ajax({
                //Definit le type de la requête
                type: "POST",
                //Le nom de la route que la requête utilise
                url: "getMessage",
                async: false,
                //Les données envoyées par la requête
                data: {
                    'lastId': lastId,
                    'idUser': idUser
                },
                success: function (text) {
                    //Récupére les données fournit en Json et les rends lisible
                    messages = JSON.parse(text);
                    //Permet d'avoir un defilement du chat du haut vers le bas
                    messages.reverse();

                }
            });
            //Definit la variable discussion avec l'element qui a l'Id "discussion"
            let discussion = document.querySelector("#discussion");
            //Créer un boucle parcourant tous les message récupérés
            for (let message of messages) {
                //Definit la variable "$file" en lui attribuant le nom du fichier associer au message           
                file = message.fileName;
                //Definit la variable "dateMessage" en lui attribuant la date de création du message
                let dateMessage = new Date(message.created_at);
                //Verifie que la variable $file n'est pas null
                if (file != null) {
                    idFile = message.id_image;
                    //Concaténe les messages récupéré avec ceux déjà present dans la conversation avec l'image associé au message
                    discussion.innerHTML = `<p>${dateMessage.toLocaleString()} ${message.userName} : ${message.content} <img src="/chat-php/public/file/display/${message.idFile}" width="300" 
                    height="200"> </p>` + discussion.innerHTML;
                    lastId = message.id;
                } else {
                    //Concaténe les messages récupéré avec ceux déjà present dans la conversation
                    discussion.innerHTML = `<p>${dateMessage.toLocaleString()} ${message.userName} : ${message.content}</p>` + discussion.innerHTML;
                    lastId = message.id;
                }

            }
        });
        //L'utilisateur n'est pas sur le chat general 
    } else {
        //Definit la variable "page" avec l'URL de la page
        page = document.location.href;
        //Definit "$idUser" en récupérant l'id present dans l'URL 
        var idUser = page.substring(page.lastIndexOf("/") + 1);
        $(document).ready(function () {
            var messagesPerso = '';
            $.ajax({
                type: "POST",
                url: "getMessage",
                async: false,
                data: {
                    'lastId': lastId,
                    'idUser': idUser
                },
                success: function (text) {
                    messagesPerso = JSON.parse(text);
                    messagesPerso.reverse();
                }
            });
            let discussionPerso = document.querySelector("#discussion_perso");

            for (let messagePerso of messagesPerso) {
                file = messagePerso.fileName;
                let dateMessagePerso = new Date(messagePerso.created_at);
                if (messagePerso.fileName != null) {
                    discussionPerso.innerHTML = `<p>${dateMessagePerso.toLocaleString()} ${messagePerso.name} : ${messagePerso.content} <img src="/chat-php/public/file/display/${messagePerso.idFile}" width="300" 
                    height="200"> </p>` + discussionPerso.innerHTML;
                    lastId = messagePerso.id;
                }
                else {
                    discussionPerso.innerHTML = `<p>${dateMessagePerso.toLocaleString()} ${messagePerso.name} : ${messagePerso.content}</p>` + discussionPerso.innerHTML;
                    lastId = messagePerso.id;
                }
            }
        });
    }
}

//Fonction qui permet de verifier que l'evenement "keyup" ne se produit que sur le touche entrer
function verifEntree(e) {
    if (e.key == "Enter") {
        ajoutMessage();
    }
}

//Fonction d'ajout de message
function ajoutMessage() {
    //Verifie si l'élément avec l'Id "texte" est present et permet de savoir que l'utilisateur est sur le chat general
    if (document.querySelector("#texte") != null) {
        //Definit l'$idUser à 0 car l'utilisateur se trouve sur le chat general
        var idUser = 0;
        //Definit la variable $message pour qu'elle contienne la value de l'element avec l'Id "texte"
        var message = document.querySelector("#texte").value;
        //Definit la variable $fileInput pour qu'elle corresponde à l'element avec l'id "file"
        var fileInput = document.querySelector('#file');
        $('#chat').off("submit").on('submit', function (e) {
            //Empeche l'actualisation aprés l'envoie du formulaire
            e.preventDefault();
            //Condition qui verifie si un fichier a été attaché
            if (fileInput.files.length != 0) {
                //Récupére le nom du fichier
                var fileName = fileInput.files[0].name;
                //Definit la variable $file pour qu'elle corresponde au fichier attaché
                file = fileInput.files[0];
                //Création d'un FormData pour fournir les données nescessaire
                var fd = new FormData();
                //Ajout des données nescessaire dans le FormData
                fd.append('message', message);
                fd.append('idUser', idUser);
                fd.append('fileName', fileName);
                fd.append('file', file);
                $.ajax({
                    processData: false,
                    contentType: false,
                    url: 'message',
                    type: 'POST',
                    data: fd
                });
                //Si aucun fichier n'a été attaché
            } else {
                //La variable $fileName est definit ""
                fileName = "";
                $.ajax({
                    url: 'message',
                    type: 'POST',
                    data: {
                        'message': message,
                        'idUser': idUser,
                        'fileName': fileName
                    }
                });
            }
            //Remise a zéro des champs avec les Id "texte" et "file"
            document.querySelector("#texte").value = "";
            document.querySelector("#file").value = "";
        });

    } else {
        //Definit la variable "page" avec l'URL de la page
        page = document.location.href;
        //Definit "$idUser" en récupérant l'id present dans l'URL 
        var idUser = page.substring(page.lastIndexOf("/") + 1);
        //Definit "$message" pour que la variable contienne la "value" de l'element avec l'id "texte_perso"
        var message = document.querySelector("#texte_perso").value;
        //Definit la variable $fileInput pour qu'elle corresponde à l'element avec l'id "file"
        var fileInput = document.querySelector('#file');
        $('#chat_perso').off("submit").on('submit', function (e) {
            e.preventDefault();
            if (fileInput.files.length != 0) {
                var fileName = fileInput.files[0].name;
                file = fileInput.files[0];
                var fd = new FormData();
                fd.append('message', message);
                fd.append('idUser', idUser);
                fd.append('fileName', fileName);
                fd.append('file', file);
                $.ajax({
                    processData: false,
                    contentType: false,
                    url: 'message',
                    type: 'POST',
                    data: fd
                });

            } else {
                fileName = "";
                $.ajax({
                    url: 'message',
                    type: 'POST',
                    data: {
                        'message': message,
                        'idUser': idUser,
                        'fileName': fileName
                    }
                });
            }
            document.querySelector("#texte_perso").value = "";
            document.querySelector("#file").value = null;
        });
    }
}

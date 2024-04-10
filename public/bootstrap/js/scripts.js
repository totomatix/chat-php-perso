var lastId = 0;

//Se produit lors du chargement de la page
window.onload = () => {
    if (document.querySelector("#texte") != null) {
        var texte = document.querySelector("#texte");
        texte.addEventListener("keyup", verifEntree);
        chargeMessage();
        setInterval(chargeMessage, 1000);
    } else {
        var texte_perso = document.querySelector('#texte_perso');
        texte_perso.addEventListener("keyup", verifEntree);
        chargeMessage();
        setInterval(chargeMessage, 1000);
    }
    if (document.querySelector('#valid') != null) {
        var valid = document.querySelector("#valid");
        valid.addEventListener("click", ajoutMessage);
    } else {
        var valid_perso = document.querySelector('#valid_perso');
        valid_perso.addEventListener("click", ajoutMessage);
    }
}

//Fonction qui permet de verifier que l'evenement "keyup" ne se produit que sur le touche entrer
function verifEntree(e) {
    if (e.key == "Enter") {
        ajoutMessage();
    }
}

//Fonction de charge des messages
function chargeMessage() {
    if (document.querySelector("#texte") != null) {
        var idUser = null;
        data = {
            'lastId': lastId,
            'idUser': idUser
        }
    } else {
        page = document.location.href;
        var idUser = page.substring(page.lastIndexOf('/') + 1);
        data = {
            'lastId': lastId,
            'idUser': idUser
        }
    }
    $.ajax({
        type: "POST",
        url: "getMessage",
        async: false,
        data: data,
        success: function (text) {
            messages = JSON.parse(text);
            messages.reverse();
        }
    });
    var discussion = document.querySelector("#discussion");
    for (var message of messages) {
        file = message.fileName;
        var dateMessage = new Date(message.created_at);
        if (file != null) {
            idFile = message.id_image;
            discussion.innerHTML = `<p>${dateMessage.toLocaleString()} ${message.userName} : ${message.content} <img src="/chat-php/public/file/display/${message.idFile}" width="300" 
                    height="200"> </p>` + discussion.innerHTML;
            lastId = message.id;
        } else {
            discussion.innerHTML = `<p>${dateMessage.toLocaleString()} ${message.userName} : ${message.content}</p>` + discussion.innerHTML;
            lastId = message.id;
        }
    }
}

//Fonction d'ajout de message
function ajoutMessage() {
    if (document.querySelector('#texte') != null) {
        var idUser = 0;
        var message = document.querySelector('#texte').value;
        var fileInput = document.querySelector('#file');
        $('#chat').off('submit').on('submit', function (e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append('message', message);
            fd.append('idUser', idUser);
            if (fileInput.files.length != 0) {
                var fileName = fileInput.files[0].name;
                file = fileInput.files[0];
                fd.append('file', file);
                fd.append('fileName', fileName);
                var data = fd;
            } else {
                fileName = "";
                fd.append('fileName', fileName);
                var data = fd;
            }
            $.ajax({
                processData: false,
                contentType: false,
                url: 'postMessage',
                type: 'POST',
                data: data
            });
            document.querySelector("#texte").value = "";
            document.querySelector("#file").value = "";
        });
    } else {
        page = document.location.href;
        var idUser = page.substring(page.lastIndexOf("/") + 1);
        var message = document.querySelector("#texte_perso").value;
        var fileInput = document.querySelector('#file');
        $('#chat_perso').off('submit').on('submit', function (e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append('message', message);
            fd.append('idUser', idUser);
            if (fileInput.files.length != 0) {
                var fileName = fileInput.files[0].name;
                file = fileInput.files[0];
                fd.append('file', file);
                fd.append('fileName', fileName);
                var data = fd;
            } else {
                fileName = "";
                fd.append('fileName', fileName);
                var data = fd;
            }
            $.ajax({
                processData: false,
                contentType: false,
                url: 'postMessage',
                type: 'POST',
                data: data
            });
            document.querySelector("#texte_perso").value = "";
            document.querySelector("#file").value = null;
        });
    }
}

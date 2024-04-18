<?php
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>';
extract($_POST);
if (isset($email) && $email != "" && isset($password) && $password != "" && isset($apiKey) && $apiKey != "") {
    echo "<script>
    $(document).ready(function() {
        $.get(\"https://dev-louveau222.users.info.unicaen.fr/bikestores/employees/email/" . $email . "?apiKey=" . $apiKey . "\", function(data) {
            if(data[0]==undefined){
                var p=document.createElement('p');
                p.innerHTML='addresse mail non recognise';
                p.classList.add('text-danger');
                $('#exampleInputEmail1').after(p);
                message('bad mail', 'red');
            }
            else if(data[0].employee_password=='" . $password . "'){
                var aujour = new Date();
                var date = new Date();
                date.setTime(aujour.getTime() + (60 * 60 * 1000));
                var expire = \"; expires=\" + date.toGMTString();
                document.cookie = \"id=yeeemploi\" + expire;
                document.cookie = \"apiKey=" . $apiKey . "\" + expire;
                document.cookie = \"store=\"+data[0].store.store_id+ expire;
                document.cookie = \"idEmploi=\"+data[0].employee_id+ expire;
                if(data[0].employee_role=='employee'){
                    document.cookie = \"navEmployee=add,modify,delete,my account\" + expire;
                    document.cookie = \"type=yeeemploi\"+expire;
                }
                else if(data[0].employee_role=='chief'){
                    document.cookie = \"navEmployee=add,modify,delete,my account,consult\" + expire;
                    document.cookie = \"type=iefch\"+expire;
                }
                else{
                    document.cookie = \"navEmployee=add,modify,delete,my account,consult\" + expire;
                    document.cookie = \"type=ti\"+expire;
                }
                window.location = \"Accueil.php\";
            }
            else{
                var p=document.createElement('p');
                p.innerHTML='bad password';
                p.classList.add('text-danger');
                $('#exampleInputPassword1').after(p);
                message('bad password', 'red');
            }
        })
        message('bad apiKey', 'red');
    });
    </script>";
}
include('index.php');

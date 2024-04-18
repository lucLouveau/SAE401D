<?php
$page = "modify";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include("www/header.php");
?>
<script src="www/js/verifEmploi.js"></script>
<script>
    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var cookie = document.cookie;
    var password;
    var p;
    var id;
    var apiKey;
    $(document).ready(function() {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            if (cookies[i].includes('idEmploi')) {
                id = cookies[i].split('=')[1];
            } else if (cookies[i].includes('apiKey')) {
                apiKey = cookies[i].split('=')[1];
            }
        }
        $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/employees/" + id + "?apiKey=" + apiKey, function(data) {
            password = data.employee_password;
            $('#store')[0].innerHTML = data.store.store_name;
            $('#role')[0].innerHTML = data.employee_role;
            $('#name')[0].value = data.employee_name;
            $('#email')[0].value = data.employee_email;
        })

    })

    function verifOld() {
        $('#oldpass')[0].innerHTML = "";
        if (password != $('#oldPassword')[0].value) {
            $('#oldpass')[0].innerHTML = "This is not your old password. Try again.";
        }
    }

    function verifCores(input) {
        if (p != undefined) {
            p.remove();
        }
        if (input.value.length < 8) {
            p = document.createElement('p');
            p.classList.add("text-danger");
            p.innerHTML = "Your password isn't to strong";
            input.after(p);
        } else if ($('#password1')[0].value != $('#password2')[0].value) {
            p = document.createElement('p');
            p.classList.add("text-danger");
            p.innerHTML = "the password are not the same!";
            input.after(p);
        }
    }

    function modifierInfo() {
        var valeurs = {
            "email": $('#email')[0].value,
            "name": $('#name')[0].value,
        }
        if (($('#password1')[0].value == $('#password2')[0].value) && ($('#password2')[0].value != "" || $('#password1')[0].value != "")) {
            valeurs['password'] = $('#password1')[0].value;
        }
        $.ajax("https://dev-louveau222.users.info.unicaen.fr/bikestores/employees/" + id + "?apiKey=" + apiKey, {
            "type": "PUT",
            "data": valeurs,
            "dataType": "html",
            "success": function(data) {
                message('Modification has been made', 'green');
            }
        })
    }
</script>
<div class="container">
    <h1>Your information</h1>

    <table class="table">
        <tbody>
            <tr>
                <td scope="col">name</td>
                <td colspan="3">
                    <input type="text" name="name" id="name">
                </td>
            </tr>
            <tr>
                <td scope="col">email</td>
                <td colspan="3">
                    <input type="email" name="email" id="email">
                </td>
            </tr>
            <tr>
                <td scope="col">password</td>
                <td>
                    <label for="oldPassword">Enter your old password</label>
                    <input type="password" name="oldPassword" id="oldPassword" onkeyup="verifOld()">
                    <p id="oldpass" class="text-danger"></p>
                </td>
                <td>
                    <label for="oldPassword">Enter the new password</label>
                    <input type="password" name="password1" id="password1" onkeyup="verifCores(this)">
                </td>
                <td>
                    <label for="oldPassword">Confirm the new password</label>
                    <input type="password" name="password2" id="password2" onkeyup="verifCores(this)">
                </td>
            </tr>
            <tr>
                <td scope="col">role</td>
                <td scope="col" id="role" colspan="3"></td>
            </tr>
            <tr>
                <td scope="col">store</td>
                <td scope="col" id="store" colspan="3"></td>
            </tr>
        </tbody>
    </table>
    <div class="button d-flex align-items-center justify-content-center">
        <button type=" submit" class="btn btn-primary" onclick="modifierInfo()">Save changes</button>
    </div>
</div>
<?php
include_once('www/footer.php');
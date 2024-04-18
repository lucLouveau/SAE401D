<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>connexion</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="www/js/message.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
        }

        .message {
            position: fixed;
            margin: 0;
            bottom: -34px;
            transition: all 1s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            z-index: 1;
        }

        .message>p {
            background-color: black;
            margin: 0;
            border-radius: 5px;
            padding: 5px;
        }
    </style>
    <script>
        <?php
        if (isset($_GET['message'])) {
            echo "document.addEventListener('DOMContentLoaded',function(){
                message('" . $_GET['message'] . "','";
            if (isset($_GET['color'])) {
                echo $_GET['color'];
            } else echo 'red';
            echo "');});";
        }
        ?>
        var id = new RegExp('id=');
        if (document.cookie != "" && id.test(document.cookie)) {
            window.location = "Accueil.php<?php if (isset($_GET['message'])) echo "?message=" . $_GET['message'] . "&color=" . $_GET['color']; ?>";
        }

        function connexion(e) {
            var aujour = new Date();
            var date = new Date();
            date.setTime(aujour.getTime() + (60 * 60 * 1000));
            var expire = "; expires=" + date.toGMTString();
            document.cookie = "id=client" + expire;
            window.location = "Accueil.php";

        }
    </script>
</head>

<body class="w-100 text-bg-dark d-flex flex-column justify-content-center align-items-center">
    <form action="connexion.php" method="POST" class="w-50 d-flex flex-column justify-content-center align-items-center">
        <div class="mb-3 w-100">
            <label for="exampleInputEmail1" class="form-labe">Email address</label>
            <input type="email" <?php if (isset($_POST['email'])) {
                                    echo "value=\"" . $_POST['email'] . "\"";
                                } ?> name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text text-light">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3 w-100">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" <?php if (isset($_POST['password'])) {
                                        echo "value=\"" . $_POST['password'] . "\"";
                                    } ?> name="password" class="form-control" id="exampleInputPassword1">
        </div>
        <div class="mb-3 w-100">
            <label for="exampleInputapiKey1" class="form-label">apiKey</label>
            <input type="apiKey" <?php if (isset($_POST['apiKey'])) {
                                        echo "value=\"" . $_POST['apiKey'] . "\"";
                                    } ?> name="apiKey" class="form-control" id="exampleInputapiKey1">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>

    </form>
    <button type="submit" id="client" class="btn btn-primary m-1" onclick="connexion(this)">I am a customer</button>
</body>

</html>
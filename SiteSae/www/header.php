<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page; ?></title>
    <style>
        * {
            z-index: 0;
        }

        main {
            min-height: calc(100vh - 8vw);
        }

        nav img {
            width: 8vw;
            max-width: 60px;
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

        footer {
            position: relative;
            width: 100%;
        }
        footer p{
            margin: 0px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="www/js/verifConnexion.js"></script>
    <script src="www/js/message.js"></script>
    <?php
    if ($page == "Index") {
        echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>';
    }
    ?>
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

        function deco(message, color) {
            var aujour = new Date();
            var date = new Date();
            date.setTime(aujour.getTime() - (10 * 24 * 60 * 60 * 1000));
            var expire = "; expires=" + date.toGMTString();
            document.cookie = "apiKey= " + expire;
            document.cookie = "id= " + expire;
            document.cookie = "navEmployee= " + expire;
            document.cookie = "store= " + expire;
            document.cookie = "type= " + expire;
            document.cookie = "idEmploi= " + expire;
            window.location = "index.php?message=" + message + "&color=" + color;
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log(window.innerHeight);
            console.log(document.documentElement.clientHeight);
        })
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
</head>

<body>
    <header class="bg-body-secondary rounded-bottom-5">
        <div class="container-fluid" style="padding-bottom:5px">
            <nav class="navbar navbar-expand navbar-light p-0">
                <img class="navbar-brand mb-0 h1 rounded-bottom-5 bg-dark-subtle p-2" src="www/img/logo.png" alt="logo">
                <div class="collapse navbar-collapse justify-content-end " id="navbarNav">
                    <ul class="navbar-nav">
                        <?php
                        foreach ($nav as $key => $value) {
                            echo "<li class=\"nav-item active d-flex align-items-center\">
                            <a href=\"$key.php\" class=\"nav-link ";
                            if ($value == $page) {
                                echo "active";
                            }
                            echo "\">$value</a>
                            </li>";
                        }
                        ?>
                    </ul>

                </div>
            </nav>
        </div>
    </header>
    <script>
        var verifEmployee = new RegExp('id=yeeemploi');
        var verifNavEmploi = new RegExp('navEmployee=');
        var verifType = new RegExp('type=');
        if (verifEmployee.test(document.cookie) || verifNavEmploi.test(document.cookie) || verifType.test(document.cookie)) {
            if (!verifEmployee.test(document.cookie) || !verifNavEmploi.test(document.cookie) || !verifType.test(document.cookie)) {
                deco();
            }
            var menu = document.cookie.split(';');
            var nav;
            for (var i = 0; i < menu.length; i++) {
                menu[i] = menu[i].split('=');
                if (menu[i][0] == " navEmployee") {
                    nav = menu[i][1].split(',');
                }
            }
            for (var i = 0; i < nav.length; i++) {
                $("header .navbar-nav")[0].innerHTML += "<li class=\"nav-item active d-flex align-items-center\"><a href=\"" + nav[i] + ".php\" class=\"nav-link\">" + nav[i][0].toUpperCase() + nav[i].slice(1) + "</a></li>";
            }
            $("header .navbar-nav")[0].innerHTML += '<button type="submit" class="btn btn-primary m-1" onclick="deco(\'deconnection\', \'green\')">Log out</button > ';
        }
    </script>
    <main>
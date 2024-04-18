<?php
$page = "Consult";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include_once('www/header.php');
?>
<h1 class="text-center">All Employees of your store</h1>
<script>
    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var cookie = document.cookie;
    var chief = new RegExp('type=iefch');
    var it = new RegExp('type=ti')
    if (!chief.test(document.cookie) && !it.test(document.cookie)) {
        window.location = "index.php?message=You does not have an access to this page&color=red";
    }
    var cookies = document.cookie.split("; ");
    for (var i = 0; i < cookies.length; i++) {
        var apiTest = new RegExp('apiKey=');
        if (apiTest.test(cookies[i])) {
            var apiKey = cookies[i].split("=")[1];
        }
    }
    var test = new RegExp('store=')
    for (var i = 0; i < cookies.length; i++) {
        if (test.test(cookies[i])) {
            var store = cookies[i].split('=')[1];
        }
    }
    $(document).ready(async function() {
        if (it.test(document.cookie)) {
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores", function(data) {
                for (var i = 0; i < data.length; i++) {
                    var table = document.createElement('table');
                    var h2 = document.createElement('h2');
                    h2.innerHTML = data[i].store_name;
                    table.innerHTML = "<thead> <tr> <th scope = \"col\" > Name </th> <th scope = \"col\" > email </th> </tr> </thead><tbody></tbody > ";
                    table.classList.add('table');
                    table.id = data[i].store_id;
                    $('.container')[0].appendChild(h2);
                    $('.container')[0].appendChild(table);
                    var chargement = document.createElement('tr');
                    chargement.classList.add("char");
                    chargement.innerHTML = '<td colspan="2" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
                    $("tbody")[i].appendChild(chargement);
                }
            })
            $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/employees?apiKey=" + apiKey, function(data) {
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = "<td>" + data[i].employee_name + "</td><td>" + data[i].employee_email + "</td>";
                    $('table#' + data[i].store.store_id + ' tbody')[0].appendChild(tr);
                    if($('table#' + data[i].store.store_id + ' tbody .char')[0]!=undefined){
                        $('table#' + data[i].store.store_id + ' tbody .char')[0].remove();
                    }
                }
                
            })  
        } else {
            var chargement = document.createElement('tr');
            chargement.innerHTML = '<td colspan="2" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
            $("tbody")[0].appendChild(chargement);
            $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/employees/store/" + store + "?apiKey=" + apiKey, function(data) {
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = "<td>" + data[i].employee_name + "</td><td>" + data[i].employee_email + "</td>";
                    $('tbody')[0].appendChild(tr);
                }
                chargement.remove();
            })
        }
    })
</script>
<div class="container">
    <?php
    if ($_COOKIE['type'] == 'iefch') {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col" >email</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>';
    }
    ?>

</div>
<?php
include_once('www/footer.php');

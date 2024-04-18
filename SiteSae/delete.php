<?php
$page = "delete";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include("www/header.php");
?>
<script src="www/js/delete.js"></script>
<script>
    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var store;
    var cookie = document.cookie;

    var cookies = document.cookie.split("; ");
    for (var i = 0; i < cookies.length; i++) {
        var apiTest = new RegExp('apiKey=');
        if (apiTest.test(cookies[i])) {
            var apiKey = cookies[i].split("=")[1];
        }
    }
    $(document).ready(async function() {
        var rech = $("#recherche")[0];
        rech.addEventListener('keyup', function() {
            recherche(this.value)
        })
        var test = new RegExp('type=ti');
        if (!test.test(document.cookie)) {
            var cookies = document.cookie.split(';');
            var test = new RegExp('store=');
            for (var i = 0; i < cookies.length; i++) {
                if (test.test(cookies[i])) {
                    store = cookies[i].split('=')[1];
                }
            }
            if (store == undefined) {
                window.location = "index.php?message=You does not have an access to this page&color=red";
            }
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores/" + store, function(data) {
                var h2 = document.createElement('h2');
                h2.innerHTML = data.store_name;
                var table = document.createElement('table');
                table.id = data.store_id;
                table.innerHTML = "<thead></thead><tbody></tbody>";
                $('.container')[0].appendChild(h2);
                $('.container')[0].appendChild(table);
            });
            var chargement = document.createElement('tr');
            chargement.classList.add("char");
            chargement.innerHTML = '<td colspan="2" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
            $("tbody")[0].appendChild(chargement);
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stocks/store/" + store, function(data) {
                var tr = document.createElement('tr');
                tr.innerHTML = "<th>Product</th><th>quantity</th>";
                $('table#' + store + '>thead')[0].appendChild(tr);
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].stock_id + "'> " + data[i].product.product_name + " </td><td>" + data[i].quantity + "</td > ";
                    $('table#' + store + '>tbody')[0].appendChild(tr);
                }
            });
            chargement.remove();
        } else {
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores", function(data) {
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement('option');
                    option.id = data[i].store_id;
                    option.innerHTML = data[i].store_name;
                    $('#store')[0].appendChild(option);
                }
            });
            var chargement = document.createElement('tr');
            chargement.classList.add("char");
            chargement.innerHTML = '<td colspan="2" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
            $(".container")[0].appendChild(chargement);
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stocks/store/1", function(data) {
                var h2 = document.createElement('h2');
                h2.innerHTML = data[1].store.store_name;
                var table = document.createElement('table');
                table.id = data[1].store.store_id;
                table.innerHTML = "<thead></thead><tbody></tbody>";
                $('.container')[0].appendChild(h2);
                $('.container')[0].appendChild(table);
                var tr = document.createElement('tr');
                tr.innerHTML = "<th>Product</th><th>quantity</th>";
                $('table#1>thead')[0].appendChild(tr);
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].stock_id + "'> " + data[i].product.product_name + " </td><td>" + data[i].quantity + "</td > ";
                    $('table#1>tbody')[0].appendChild(tr);
                }
                chargement.remove();
            });
        }
        var tr = document.createElement('tr');
        tr.innerHTML = "<td>no stocks with this name</td>";
        $('table>tbody')[0].appendChild(tr);
        tr.classList.add('d-none');
        tr.classList.add('erreur');


    });

    async function miseAJour() {
        $("#recherche")[0].value = "";
        var type = $('#type')[0].value;
        var test = new RegExp('type=ti');
        if (test.test(document.cookie)) {
            store = $('#store')[0].selectedOptions[0].id;
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores/" + store, function(data) {
                $('h2')[0].innerHTML = data.store_name;
            });
            $("table")[0].id = store;
        }
        $('table#' + store + '>tbody')[0].innerHTML = "";
        var chargement = document.createElement('tr');
        chargement.classList.add("char");
        chargement.innerHTML = '<td colspan="2" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
        $("tbody")[0].appendChild(chargement);
        if (type == "Employees") {
            $('table#' + store + '>thead')[0].innerHTML = "<tr><th>Name</th><th>Email</th></tr>";
        } else if (type == "Products") {
            $('h2')[0].innerHTML = "Products";
            $('table#' + store + '>thead')[0].innerHTML = "<tr><th>Name</th><th>Category</th><th>Brand</th></tr>";
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/" + type, async function(data) {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].stocks.length == 0) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].product_id + "'>" + data[i].product_name + "</td><td>" + data[i].brand.brand_name + "</td><td>" + data[i].category.category_name + "</td>";
                        $('table>tbody')[0].appendChild(tr);
                    }
                }
            });
        } else if (type == "Brands") {
            $('h2')[0].innerHTML = "Brands";
            $('table#' + store + '>thead')[0].innerHTML = "<tr><th>Name</th></tr>";
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/" + type, async function(data) {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].products.length == 0) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].brand_id + "'>" + data[i].brand_name + "</td>";
                        $('table>tbody')[0].appendChild(tr);
                    }
                }

            });
        } else if (type == "Categories") {
            $('h2')[0].innerHTML = "Category";
            $('table#' + store + '>thead')[0].innerHTML = "<tr><th>Name</th></tr>";
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/" + type, async function(data) {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].products.length == 0) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].category_id + "'>" + data[i].category_name + "</td>";
                        $('table>tbody')[0].appendChild(tr);
                    }
                }

            });
        } else if (type == "Stores") {
            $('h2')[0].innerHTML = "Store";
            $('table#' + store + '>thead')[0].innerHTML = "<tr><th>Name</th></tr>";
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/" + type, async function(data) {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].employees.length == 0) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].store_id + "'>" + data[i].store_name + "</td>";
                        $('table>tbody')[0].appendChild(tr);
                    }
                }

            });
        } else {
            $('table#' + store + '>thead')[0].innerHTML = "<tr><th>Product</th><th>quantity</th></tr>";
        }
        if (type == "Employees" || type == "Stocks") {
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/" + type + "/store/" + store + "?apiKey=" + apiKey, function(data) {
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement('tr');
                    if (type == "Employees") {
                        tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].employee_id + "'>" + data[i].employee_name + "</td><td>" + data[i].employee_email + "</td>";
                    } else {
                        tr.innerHTML = "<td onclick=\"deletThis(this)\" class='" + data[i].stock_id + "'> " + data[i].product.product_name + " </td><td>" + data[i].quantity + "</td > ";
                    }
                    $('table#' + store + '>tbody')[0].appendChild(tr);
                }
            });
        }
        chargement.remove();
        if ($('table>tbody')[0].children.length == 0) {
            var tr = document.createElement('tr');
            tr.innerHTML = "<td>no " + type + " can be deleted</td>";
            $('table>tbody')[0].appendChild(tr);
        }
        var tr = document.createElement('tr');
        tr.innerHTML = "<td>no " + type + " with this name</td>";
        $('table>tbody')[0].appendChild(tr);
        tr.classList.add('d-none');
        tr.classList.add('erreur');


    }

    function recherche(valeur) {
        $('.erreur')[0].classList.add('d-none');
        var trs = $("tbody>tr");
        for (var i = 0; i < trs.length; i++) {
            trs[i].classList.add("d-none");
            for (var j = 0; j < trs[i].children.length - 1; j++) {
                if (trs[i].children[0].innerHTML.toLowerCase().includes(valeur.toLowerCase())) {
                    trs[i].classList.remove('d-none');
                }
            }
        }
        if ($('tbody>tr.d-none').length == $("tbody>tr").length) {
            $('.erreur')[0].classList.remove('d-none');
        }
    }
    1

    async function deletThis(td) {
        var id = td.classList[0];
        var type = "Stocks";
        if ($('#type')[0] != undefined) {
            type = $('#type')[0].value;
        }
        if (confirm('Are you sur?')) {
            delet(type, id, apiKey);
        }
    }
</script>

<?php
if ($_COOKIE['type'] == "yeeemploi") {
    echo "<h1>Delete stock from your store</h1>";
} else if ($_COOKIE['type'] == "iefch") {
    echo "<h1>Delete things from your store</h1><select id=\"type\" onchange='miseAJour()'>
    <option value='Stocks'>Stocks</option>
    <option value='Employees'>Employees</option></select>";
} else {
    echo "<h1>Delete things from all store</h1>
    <select id=\"type\" onchange='miseAJour()'>
    <option value='Stocks'>Stocks</option>
    <option value='Products'>Products</option>
    <option value='Brands'>Brands</option>
    <option value='Categories'>Categroy</option>
    <option value='Employees'>Employees</option>
    <option value='Stores'>Stores</option></select>
    <select id=\"store\" onchange='miseAJour()'></select>";
}
?>
<input type="text" name="recherche" placeholder="recherche..." id="recherche">
<div class="container">

</div>
<?php
include_once('www/footer.php');
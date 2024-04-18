<?php
$page = "modify";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include("www/header.php");
?>
<script>
    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var cookie = document.cookie;
    var store = "";
    var apiKey;
    $(document).ready(function() {
        var cookies = document.cookie.split(';');
        var test = new RegExp('store=')
        for (var i = 0; i < cookies.length; i++) {
            if (test.test(cookies[i])) {
                store = cookies[i].split('=')[1];
            }
        }
        for (var i = 0; i < cookies.length; i++) {
            var apiTest = new RegExp('apiKey=');
            if (apiTest.test(cookies[i])) {
                apiKey = cookies[i].split("=")[1];
            }
        }
        if (store == "") {
            window.location = "index.php?message=You does not have an access to this page&color=red";
        }
        $('.Stock').hide();
        $('.Store').hide();
        $('.Employee').hide();
        $('.Brand').hide();
        $('.Category').hide();
        if ($('#storeid')[0] != undefined) {
            $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores", function(data) {
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement('option');
                    option.value = data[i].store_id;
                    option.innerHTML = data[i].store_name;
                    $('#storeid')[0].appendChild(option);
                }

            })
        }
        if ($('#employee')[0] != undefined) {
            $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/employees?apiKey=" + apiKey, function(data) {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].employee_role != "it") {
                        var option = document.createElement('option');
                        option.value = data[i].employee_id;
                        option.innerHTML = data[i].employee_name;
                        $('#employee')[0].appendChild(option);
                    }
                }

            })
        }
        if ($('#brand')[0] != undefined) {
            $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/brands", function(data) {
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement('option');
                    option.value = data[i].brand_id;
                    option.innerHTML = data[i].brand_name;
                    $('#brand')[0].appendChild(option);
                }

            })
        }
        if ($('#category')[0] != undefined) {
            $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/categories", function(data) {
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement('option');
                    option.value = data[i].category_id;
                    option.innerHTML = data[i].category_name;
                    $('#category')[0].appendChild(option);
                }

            })
        }
    });

    async function choosed() {
        var select = $('#selectModif')[0].value;
        if ($('#storeid')[0] != undefined) {
            store = $('#storeid')[0].value;
        }
        $('.Stock').hide();
        $('.Store').hide();
        $('.Employee').hide();
        $('.Brand').hide();
        $('.Category').hide();
        $('.' + select).show();
        if (select == "Stock") {
            while ($('.Stock tbody')[0].children[0] != undefined) {
                $('.Stock tbody')[0].children[0].remove();
            }
            var chargement = document.createElement('tr');
            chargement.classList.add("char");
            chargement.innerHTML = '<td colspan="2" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
            $(".Stock tbody")[0].appendChild(chargement);
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stocks/store/" + store, function(data) {
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement('tr');
                    tr.id = data[i].stock_id;
                    var product = document.createElement('td')
                    product.innerHTML = data[i].product.product_name;
                    var quantity = document.createElement('td')
                    quantity.innerHTML = "<input type='number' value='" + data[i].quantity + "' >";
                    quantity.addEventListener('keypress', function(event) {
                        if (event.key == "Enter") {
                            modif(select + "s", event.target.parentElement.parentElement);
                        }
                    })
                    tr.appendChild(product);
                    tr.appendChild(quantity);
                    $('.Stock tbody')[0].appendChild(tr);
                }


            })
            chargement.remove();
        } else if (select == "Store") {
            var chargement = document.createElement('tr');
            chargement.classList.add("char");
            chargement.innerHTML = '<td colspan="2" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
            $(".Store")[0].children[0].before(chargement);
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores/" + store, function(data) {
                var form = $('.Store')[0].children[1].children;
                form[1].value = data.store_name;
                form[3].value = data.phone;
                form[5].value = data.email;
                form[7].value = data.street;
                form[9].value = data.city;
                form[11].value = data.state;
                form[13].value = data.zip_code;
            })
            chargement.remove();
        }

    }

    function modif(type, tr) {
        var cookies = document.cookie.split("; ");
        for (var i = 0; i < cookies.length; i++) {
            var apiTest = new RegExp('apiKey=');
            if (apiTest.test(cookies[i])) {
                var apiKey = cookies[i].split("=")[1];
            }
        }
        $.ajax("https://dev-louveau222.users.info.unicaen.fr/bikestores/" + type + "/" + tr.id + "?apiKey=" + apiKey, {
            "type": "PUT",
            "data": {
                "stock_id": 1,
                "quantity": tr.children[1].children[0].value
            },
            "dataType": "html",
            "success": function(data) {
                message(type + " n°" + tr.id + " modifed", "green");
            }
        })
    }

    function modifEle(div) {
        var params = div.children;
        var sel = div.parentElement.id;
        var erreur = "";
        var valeur = new Object();
        for (var i = 0; i < params.length; i++) {
            if (params[i].tagName == "SELECT") {
                if (params[i].id == 'storeSel') {
                    store = params[i].value;
                }
                valeur[params[i].name] = params[i].value;
            } else if (params[i].tagName == "INPUT") {
                if (params[i].value == "") {
                    erreur += params[i].name + ", ";
                } else if (params[i].type == "email") {
                    var regex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
                    if (regex.test(params[i].value)) {
                        valeur[params[i].name] = params[i].value;
                    } else {
                        erreur += params[i].name + ", ";
                    }
                } else if (params[i].type == "password") {
                    if (params[i].value.length >= 8) {
                        var lettreMin = /[a-z]+/;
                        var lettreMaj = /[A-Z]+/;
                        var chiffre = /[0-9]+/;
                        var caractère = /[#\.\?!@\$%\^&\*-_]/;
                        if (lettreMaj.test(params[i].value) && lettreMin.test(params[i].value) && chiffre.test(params[i].value) && caractère.test(params[i].value)) {
                            valeur[params[i].name] = params[i].value;
                        } else {
                            erreur += params[i].name + ", ";
                        }
                    } else {
                        erreur += params[i].name + ", ";
                    }
                } else {
                    valeur[params[i].name] = params[i].value;
                }
            }


        }
        if ($('#storeid')[0] != undefined && sel == "Stores") {
            store = $('#storeid')[0].value;
        } else if (sel == "Employees") {
            store = $('#employee')[0].value;
        } else if (sel == "Brands") {
            store = $('#brand')[0].value;
        } else if (sel == "Categories") {
            store = $('#category')[0].value;
        }
        $.ajax("https://dev-louveau222.users.info.unicaen.fr/bikestores/" + sel + "/" + store + "?apiKey=" + apiKey, {
            "type": "PUT",
            "data": valeur,
            "dataType": "html",
            "success": function(data) {
                message(sel + " n°" + store + " modifed", "green");
            }
        })
    }

    async function infoEmploy(id) {
        $('#employeeSel').show();
        var chargement = document.createElement('div');
        chargement.classList.add("char");
        chargement.innerHTML = '<p class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></p>';
        $(".Employee")[0].children[0].before(chargement);
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/employees/" + id + "?apiKey=" + apiKey, function(data) {
            if (data.employee_role == "chief") {
                $('#employeeSel option:nth-of-type(2)')[0].selected = true;
            } else {
                $('#employeeSel option:first-of-type')[0].selected = true;
            }


        })
        chargement.remove();
    }

    async function infoBrand(id) {
        var chargement = document.createElement('div');
        chargement.classList.add("char");
        chargement.innerHTML = '<p class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></p>';
        $(".Brand")[0].children[0].before(chargement);
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/brands/" + id.value + "?apiKey=" + apiKey, function(data) {
            id.nextElementSibling.children[1].value = data.brand_name;
        })
        chargement.remove();
    }

    async function infoCate(id) {
        var chargement = document.createElement('div');
        chargement.classList.add("char");
        chargement.innerHTML = '<p class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></p>';
        $(".Category")[0].children[0].before(chargement);
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/categories/" + id.value + "?apiKey=" + apiKey, function(data) {
            id.nextElementSibling.children[1].value = data.category_name;
        })
        chargement.remove();
    }
</script>
<script src="www/js/ajouter.js"></script>
<h1>Modify your stocks</h1>
<select name="option" id="selectModif" onchange="choosed()">
    <option value="false">choose what you want to modify</option>
    <option value="Stock">A stock from your store</option>
    <?php
    if ($_COOKIE['type'] == "iefch") {
        echo '<option value="Store">your store</option>';
    } else if ($_COOKIE['type'] == "ti") {
        echo '<option value="Store">One of all stores</option>
        <option value="Employee">One employee</option>
        <option value="Brand">One brand</option>
        <option value="Category">One Category</option>';
    }
    ?>
</select>
<?php
if ($_COOKIE['type'] == "ti") {
    echo "<select id='storeid' onchange='choosed()'></select>";
}
?>
<p class="text-danger">Don't forget to press 'Enter' after one modification!</p>
<div style="max-height:700px;overflow-y:scroll;" class="Stock">
    <table class="table">
        <thead>
            <tr>
                <th scope="col" onclick="trie(this)">product</th>
                <th scope="col" onclick="trie(this)">quantity</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="Store container" id="Stores">
    <div class="mb-3 w-100">
        <label for="name" class="form-labe w-100">Store name(*)</label>
        <input type="text" name="name" id="name" placeholder="name">
        <label for="name" class="form-labe w-100">Phone number(*)</label>
        <input type="tel" name="phone" id="phone" placeholder="phone number">
        <label for="name" class="form-labe w-100">Email(*)</label>
        <input type="email" name="email" id="email" placeholder="email">
        <label for="name" class="form-labe w-100">Street(*)</label>
        <input type="text" name="street" id="street" placeholder="street">
        <label for="name" class="form-labe w-100">city(*)</label>
        <input type="text" name="city" id="city" placeholder="city">
        <label for="name" class="form-labe w-100">state(*)</label>
        <input type="text" name="state" id="state" placeholder="state">
        <label for="name" class="form-labe w-100">Code(*)</label>
        <input type="text" name="zip_code" id="zip_code" placeholder="code">
    </div>
    <button type=" submit" class="btn btn-primary" onclick="modifEle(this.parentElement.children[0])">save the modification</button>
</div>

<?php
if ($_COOKIE['type'] == 'ti') {
    echo '
    <div class="Employee container" id="Employees">
        <select name="employee" id="employee" onchange="infoEmploy(this.value)">
            <option>Select one of all employee</option>
        </select>
        <div class="mb-3 w-100">
            <select id="employeeSel" style="display:none;" name="role">
                <option value="employee">employee</option>
                <option value="chief">chief</option>
            </select>
        </div>
        <button type=" submit" class="btn btn-primary" onclick="modifEle(this.parentElement.children[1])">save the modification</button>
    </div>
    <div class="Brand container" id="Brands">
        <select name="brand" id="brand" onchange="infoBrand(this)">
            <option>Select one of all brand</option>
        </select>
        <div class="mb-3 w-100">
            <label for="name" class="form-labe w-100">Brand name(*)</label>
            <input type="text" name="name" id="name" placeholder="name">
        </div>
        <button type=" submit" class="btn btn-primary" onclick="modifEle(this.parentElement.children[1])">save the modification</button>
    </div>
    <div class="Category container" id="Categories">
        <select name="category" id="category" onchange="infoCate(this)">
            <option>Select one of all category</option>
        </select>
        <div class="mb-3 w-100">
            <label for="name" class="form-labe w-100">Category name(*)</label>
            <input type="text" name="name" id="name" placeholder="name">
        </div>
        <button type=" submit" class="btn btn-primary" onclick="modifEle(this.parentElement.children[1])">save the modification</button>
    </div>
        ';
}
?>
<?php
include_once('www/footer.php');
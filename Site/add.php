<?php
$page = "add";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include("www/header.php");
?>
<script>
    var store = "";

    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var cookie = document.cookie;
    $(document).ready(function() {
        var cookies = document.cookie.split(';');
        var test = new RegExp('store=')
        for (var i = 0; i < cookies.length; i++) {
            if (test.test(cookies[i])) {
                store = cookies[i].split('=')[1];
            }
        }
        if (store == "") {
            window.location = "index.php?message=You does not have an access to this page&color=red";
        }

        init();
        $('#product').hide();
        $('#employee').hide();
        $('#brand').hide();
        $('#categorie').hide();
        if ($('#store')[0] != undefined) {
            $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores", function(data) {
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement('option');
                    option.value = data[i].store_id;
                    option.innerHTML = data[i].store_name;
                    $('#storeSel')[0].appendChild(option);
                    if ($('#storeStockSel')[0] != undefined) {
                        var option = document.createElement('option');
                        option.value = data[i].store_id;
                        option.innerHTML = data[i].store_name;
                        $('#storeStockSel')[0].appendChild(option);
                    }
                }
            })
            $('#store').hide();
        }
    });

    async function init() {
        miseAJour(1);
        $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/brands", function(data) {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement('option');
                option.value = data[i].brand_id;
                option.innerHTML = data[i].brand_name;
                $('select#Brand')[0].appendChild(option);
            }
        })
        $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/categories", function(data) {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement('option');
                option.value = data[i].category_id;
                option.innerHTML = data[i].category_name;
                $('select#Category')[0].appendChild(option);
            }
        })


    }

    async function miseAJour(sel) {
        $('select#Product')[0].innerHTML = "";
        var store = sel;
        var products = new Array();
        var stocks = new Array();
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stocks/store/" + store, function(data) {
            for (var i = 0; i < data.length; i++) {
                stocks.push(data[i].product.product_id);
            }
        })
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/products", function(data) {
            for (var i = 0; i < data.length; i++) {
                if (!stocks.includes(data[i].product_id)) {
                    products.push(data[i]);
                }
            }
        })
        for (var i = 0; i < products.length; i++) {
            var option = document.createElement('option');
            option.value = products[i].product_id;
            option.innerHTML = products[i].product_name;
            $('select#Product')[0].appendChild(option);
        }
    }

    function afficheForm(sel) {
        $('#employee').hide();
        $('#product').hide();
        $('#stock').hide();
        $('#store').hide();
        $('#brand').hide();
        $('#categorie').hide();
        $('#' + sel.selectedOptions[0].value).show()
        $('#' + sel.selectedOptions[0].value).style = "display:flex;";
    }
</script>
<script src="www/js/ajouter.js"></script>
<h1>Add a product or a stock</h1>
<label for="choix">choose what you want to add: </label>
<select name="choix" id="choix" onchange="afficheForm(this)">
    <option value="stock">Stock</option>
    <option value="product">Product</option>
    <?php
    if ($_COOKIE['type'] == "iefch" || $_COOKIE['type'] == "ti") {
        echo "<option value=\"employee\">Employee</option>
        <option value=\"brand\">Brand</option>
        <option value=\"categorie\">Category</option>";
    }
    if ($_COOKIE['type'] == "ti") {
        echo "<option value=\"store\">Store</option>";
    }
    ?>
</select>
<p class="text-danger">(*) = mandatory field</p>

<!--stock-->
<div class="d-flex justify-content-center">
    <div id="stock" class="w-50 flex-column">
        <div class="mb-3 w-100">
            <?php
            if ($_COOKIE['type'] == "ti") {
                echo "<select name='storeStock' id='storeStockSel' onchange='miseAJour(this.value)'></select>";
            }
            ?>
            <label for="name" class="form-labe w-100">Product name</label>
            <select name="product" id="Product">

            </select>
            <label for="name" class="form-labe w-100">Quantity </label>
            <input type="number" name="quantity" id="quantity" value="50" min="1">
        </div>
        <button type=" submit" class="btn btn-primary" onclick="ajout(this,store)">Submit</button>
    </div>
</div>

<!--product-->
<div class="d-flex justify-content-center">
    <div id="product" class="w-50 flex-column">
        <div class="mb-3 w-100">
            <label for="name" class="form-labe w-100">Product name(*)</label>
            <input type="text" name="name" placeholder="torque">
            <label for="name" class="form-labe w-100">Brand name</label>
            <select name="brand" id="Brand">

            </select>
            <label for="name" class="form-labe w-100">Category name</label>
            <select name="category" id="Category">

            </select>
            <label for="name" class="form-labe w-100">Product year(*)</label>
            <input type="number" name="year" min='1700' value='2024' max='2025'>
            <label for="name" class="form-labe w-100">Price(*)</label>
            <input type="number" name="price" value="999.99" step="0.01">

        </div>
        <button type=" submit" class="btn btn-primary" onclick="ajout(this,store)">Submit</button>
    </div>
</div>

<!--brand-->
<div class="d-flex justify-content-center">
    <div id="brand" class="w-50 flex-column">
        <div class="mb-3 w-100">
            <label for="name" class="form-labe w-100">Brand name(*)</label>
            <input type="text" name="name" placeholder="name">
        </div>
        <button type=" submit" class="btn btn-primary" onclick="ajout(this,store)">Submit</button>
    </div>
</div>

<!--category-->
<div class="d-flex justify-content-center">
    <div id="categorie" class="w-50 flex-column">
        <div class="mb-3 w-100">
            <label for="name" class="form-labe w-100">Category name(*)</label>
            <input type="text" name="name" placeholder="name">
        </div>
        <button type=" submit" class="btn btn-primary" onclick="ajout(this,store)">Submit</button>
    </div>
</div>

<!--employee-->
<?php
if ($_COOKIE['type'] == "iefch" || $_COOKIE['type'] == "ti") {
    echo '<div class="d-flex justify-content-center">
        <div id="employee" class="w-50 flex-column">
            <div class="mb-3 w-100">
                <label for="name" class="form-labe w-100">Employee name</label>
                <input type="text" name="name"  placeholder="name">
                <label for="name" class="form-labe w-100">Email </label>
                <input type="email" name="email" id="email">
                <label for="name" class="form-labe w-100">Password </label>
                <input type="password" name="password" id="password">
        ';
    if ($_COOKIE['type'] == "iefch") {
        echo '<input type="hidden" name="role" id="role" value="employee">';
    } else {
        echo '
        <label class="form-labe w-100">Employee store</label><select name="store" id="storeSel"></select>
        <label class="form-labe w-100">Employee role</label><select name="role" id="role">
        <option value="employee">employee</option>
        <option value="chief">chief</option></select>';
    }
    echo '</div>
            <button type=" submit" class="btn btn-primary" onclick="ajout(this,store)">Submit</button>
        </div>
    </div>';
}

//store
if ($_COOKIE['type'] == "ti") {
    echo '<div class="d-flex justify-content-center">
    <div id="store" class="w-50 flex-column">
        <div class="mb-3 w-100">
            <label for="name" class="form-labe w-100">Store name(*)</label>
            <input type="text" name="name"  placeholder="name">
            <label for="name" class="form-labe w-100">Phone number(*)</label>
            <input type="tel" name="phone" id="phone" placeholder="phone number">
            <label for="name" class="form-labe w-100">Email(*)</label>
            <input type="email" name="email"  placeholder="email">
            <label for="name" class="form-labe w-100">Street(*)</label>
            <input type="text" name="street" id="street" placeholder="street">
            <label for="name" class="form-labe w-100">city(*)</label>
            <input type="text" name="city" id="city" placeholder="city">
            <label for="name" class="form-labe w-100">state(*)</label>
            <input type="text" name="state" id="state" placeholder="state">
            <label for="name" class="form-labe w-100">Code(*)</label>
            <input type="text" name="zip_code" id="zip_code" placeholder="code">
        </div>
        <button type=" submit" class="btn btn-primary" onclick="ajout(this,store)">Submit</button>
    </div>
</div>';
}
?>
<?php
include_once('www/footer.php');
<?php
$page = "Product";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include_once('www/header.php');
?>
<h1 class="text-center">Product</h1>
<p class="marque d-inline-block w-50 text-center">Brand
</p>
<p class="categorie d-inline-block text-center">Category</p>
<script>
    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var cookie = document.cookie;
    var stock = new Array();
    var product;
    $(document).ready(async function() {
        $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/products/" + <?php echo $_GET['id'] ?>, function(data) {
            var ele = data;
            $("h1")[0].innerHTML = data.product_name;
            $(".marque")[0].innerHTML = "Brand: <a class='text-dark' href=\"marque.php?id=" + ele.brand.brand_id + "\">" + ele.brand.brand_name + "</a>";
            $(".categorie")[0].innerHTML = "Category: <a class='text-dark' href=\"category.php?id=" + ele.category.category_id + "\">" + ele.category.category_name + "</a>";
        })
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/products/" + <?php echo $_GET['id'] ?> + "/stocks", function(data) {
            var ele = data;

            console.log(data);
            for (var i = 0; i < ele.length; i++) {
                var card = "";
                card += '<div class="card" style="width: 18rem;"><img src="www/img/store' + ele[i].store.store_id + '.jpg" class="card-img-top" alt="store' + ele[i].store.store_id + '"><div class="card-body"><h5 class="card-title">' + ele[i].store.store_name + '</h5><p class="card-text">';
                if (ele[i].quantity >= 10) card += "Always ";
                else card += "Only remains ";
                $(".contain-card")[0].innerHTML += card + ' <strong>' + ele[i].quantity + '</strong> products in the store</p><a href="store.php?id=' + ele[i].store.store_id + '" class="btn btn-primary">Go to the store</a></div></div>';
                stock.push(ele[i]);
            }

        })


    });
</script>
<div class="stores container contain-card d-flex flex-wrap justify-content-between align-items-center"></div>

<?php
include_once('www/footer.php');

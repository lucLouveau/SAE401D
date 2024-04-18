<?php
$page = "Products";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include_once('www/header.php');
?>
<h1 class="text-center">All products</h1>
<script src="www/js/trie.js"></script>
<script>
    var pageActu = 0;
    var maxPage;
    var rechePos = true;

    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var cookie = document.cookie;
    $(document).ready(async function() {
        recherche('');
        var rech = $("#recherche")[0];
        rech.addEventListener('keyup', function() {
            recherche(this.value)
        })
    });

    function page(dir) {
        if (dir == "+") {
            pageActu++;
        } else {
            pageActu--;
        }
        recherche($("#recherche")[0].value);
    }
    async function recherche(valeur) {
        if (rechePos == true) {
            $('#page')[0].innerHTML = "";
            rechePos = false;
            $("tbody")[0].innerHTML = "";
            var chargement = document.createElement('tr');
            chargement.innerHTML = '<td colspan="5" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
            $("tbody")[0].appendChild(chargement);
            await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/products/name/" + valeur, function(data) {
                maxPage = data.length / 50;
                if (pageActu * 50 + 50 > data.length) {
                    var length = data.length;
                } else {
                    var length = pageActu * 50 + 50;
                }
                for (var i = (pageActu * 50); i < length; i++) {
                    var ele = data[i];
                    var tr = document.createElement('tr');
                    tr.innerHTML = "<td id='Name' scope=\"col\"><a class='text-decoration-none' href=\"produit.php?id=" + ele.product_id + "\">" + ele.product_name + "</a></td><td id='Category' scope = \"col\"><a class='text-decoration-none' href=\"category.php?id=" + ele.category.category_id + "\">" + ele.category.category_name + "</a></td> <td id='Brand' scope = \"col\"><a class='text-decoration-none' href=\"marque.php?id=" + ele.brand.brand_id + "\">" + ele.brand.brand_name + "</a></td> <td id='Year' scope = \"col\">" + ele.model_year + "</td> <td id='Price' scope = \"col\">" + ele.list_price + "$</td>";
                    $("tbody")[0].appendChild(tr);
                }
                chargement.remove();
            })
            if (pageActu != 0) {
                $('#page')[0].innerHTML += "<button onclick=\"page('-')\" class='float-start btn btn-primary m-1'>Previous page</button>";
            }
            if (pageActu < Math.trunc(maxPage)) {
                $('#page')[0].innerHTML += "<button onclick=\"page('+')\" class='float-end btn btn-primary m-1'>next page</button>";
            }
            rechePos = true;
            if (valeur != $("#recherche")[0].value) recherche($("#recherche")[0].value);
        }
    }

    var ordres = new Array(1, 1, 1, 1, 1);

    async function tri(e) {
        await trie(e, ordres[e]);
        if (ordres[e] == 1) {
            ordres[e] = 0;
        } else if (ordres[e] == 0) {
            ordres[e] = 1;
        }
    }
</script>
<div class="container">
    <input type="text" name="recherche" placeholder="recherche..." id="recherche">
    <div style="max-height:700px;overflow-y:scroll;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" onclick="tri(0)">Name</th>
                    <th scope="col" onclick="tri(1)">Category</th>
                    <th scope="col" onclick="tri(2)">Brand</th>
                    <th scope="col" onclick="tri(3)">Year</th>
                    <th scope="col" onclick="tri(4)">Price</th>
                </tr>
            </thead>

            <tbody>


            </tbody>

        </table>
    </div>
    <?php
    echo "<div id='page'></div>" ?>
</div>
<?php
include_once('www/footer.php');

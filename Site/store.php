<?php
$page = "Store";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include_once('www/header.php');
if (isset($_GET['p'])) {
    $pageActu = $_GET['p'];
} else {
    $pageActu = 0;
}
?>
<h1 class="text-center">Store </h1>
<script src="www/js/trie.js"></script>
<script>
    function checkCookie() {
        if (cookie != document.cookie) {
            deco();
        }
    }
    var cookie = document.cookie;
    $(document).ready(async function() {
        var maxPage;
        $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores/" + <?php echo $_GET['id'] ?>, function(data) {
            var ele = data;
            $("h1")[0].innerHTML = data.store_name;
        })
        var chargement = document.createElement('tr');
        chargement.innerHTML = '<td colspan="3" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
        $("tbody")[0].appendChild(chargement);
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores/" + <?php echo $_GET['id'] ?> + "/stocks", function(data) {
            var ele = data;

            maxPage = ele.length / 50;
            if (<?php echo $pageActu * 50 + 50 ?> > data.length) {
                    var length = data.length;
                } else {
                    var length = <?php echo $pageActu * 50 + 50 ?>;
                }
            for (var i = <?php echo $pageActu * 50 ?>; i < length ; i++) {
                var tr = document.createElement('tr');
                tr.innerHTML = "<td id='Name' scope=\"col\"><a class='text-decoration-none' href=\"produit.php?id=" + ele[i].product.product_id + "\">" + ele[i].product.product_name + "</a></td><td>" + ele[i].product.price + "$</td><td>" + ele[i].quantity + "</td>";
                $("tbody")[0].appendChild(tr);
            }
            
        })
        chargement.remove();
        var rech = $("#recherche")[0];
        rech.addEventListener('keyup', function() {
            recherche(this.value)
        });
        if(<?php echo $pageActu?> != 0){
            var button = document.createElement('button');
            button.innerHTML = "<a class='link-light' href='store.php?id=<?php echo $_GET['id'] ?>&p=<?php echo $pageActu - 1 ?>'>previous page</a>";
            button.classList.add('float-start');
            button.classList.add('btn-primary');
            button.classList.add('btn');
            button.classList.add('m-1');
            $('#page')[0].appendChild(button);
        }
        if (Math.trunc(maxPage) > <?php echo $pageActu ?>) {
            var button = document.createElement('button');
            button.innerHTML = "<a class='link-light' href='store.php?id=<?php echo $_GET['id'] ?>&p=<?php echo $pageActu + 1 ?>'>next page</a>";
            button.classList.add('float-end');
            button.classList.add('btn-primary');
            button.classList.add('btn');
            button.classList.add('m-1');
            $('#page')[0].appendChild(button);
        }

    });

    function recherche(valeur) {
        var trs = $("tbody>tr");
        for (var i = 0; i < trs.length; i++) {
            trs[i].classList.add("d-none");
            for (var j = 0; j < trs[i].children.length; j++) {
                if (trs[i].children[0].children[0].innerHTML.toLowerCase().includes(valeur.toLowerCase())) {
                    trs[i].classList.remove('d-none');
                }
            }
        }
    }

    var ordres = new Array(1, 1, 1);

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
                    <th scope="col" onclick="tri(0)">Product</th>
                    <th scope="col" onclick="tri(1)">Price</th>
                    <th scope="col">Quantity</th>
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

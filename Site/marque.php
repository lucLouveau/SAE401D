<?php
$page = "Brand";
$nav = array("Accueil"=>"Index", "Produits"=>"Products");
include_once('www/header.php');
if (isset($_GET['p'])) {
    $pageActu = $_GET['p'];
} else {
    $pageActu = 0;
}
?>
<h1 class="text-center">Brand </h1>
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
        $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/brands/" + <?php echo $_GET['id'] ?>, function(data) {
            var ele = data;
            $("h1")[0].innerHTML = ele.brand_name;
        })
        var chargement = document.createElement('tr');
        chargement.innerHTML = '<td colspan="3" class="text-center"><i class="fa fa-spinner fa-5x fa-pulse"></i></td>';
        $("tbody")[0].appendChild(chargement);
        await $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/brands/" + <?php echo $_GET['id'] ?> + "/products", function(data) {
            maxPage = data.length / 50;
            if (<?php echo $pageActu * 50 + 50 ?> > data.length) {
                var length = data.length;
            } else {
                var length = <?php echo $pageActu * 50 + 50 ?>;
            }
            for (var i = <?php echo $pageActu * 50 ?>; i < length; i++) {
                var ele = data[i];
                var tr = document.createElement('tr');
                tr.innerHTML = "<td id='Name' scope=\"col\"><a class='text-decoration-none' href=\"produit.php?id=" + ele.product_id + "\">" + ele.product_name + "</a></td> <td id='Category' scope = \"col\"><a class='text-decoration-none' href=\"category.php?id=" + ele.category.category_id + "\">" + ele.category.category_name + "</a></td><td id='Price' scope = \"col\">" + ele.list_price + "$</td>";
                $("tbody")[0].appendChild(tr);
            }
            
        })
        chargement.remove();
        if(<?php echo $pageActu?> != 0){
            var button = document.createElement('button');
            button.innerHTML = "<a class='link-light' href='marque.php?id=<?php echo $_GET['id'] ?>&p=<?php echo $pageActu - 1 ?>'>previous page</a>";
            button.classList.add('float-start');
            button.classList.add('btn-primary');
            button.classList.add('btn');
            button.classList.add('m-1');
            $('#page')[0].appendChild(button);
        }
        if (Math.trunc(maxPage) > <?php echo $pageActu ?>) {
            var button = document.createElement('button');
            button.innerHTML = "<a class='link-light' href='marque.php?id=<?php echo $_GET['id'] ?>&p=<?php echo $pageActu + 1 ?>'>next page</a>";
            button.classList.add('float-end');
            button.classList.add('btn-primary');
            button.classList.add('btn');
            button.classList.add('m-1');
            $('#page')[0].appendChild(button);
        }
    });
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
    <div style="max-height:700px;overflow-y:scroll;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" onclick="tri(0)">Name</th>
                    <th scope="col" onclick="tri(1)">Category</th>
                    <th scope="col" onclick="tri(2)">Price</th>
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

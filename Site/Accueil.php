<?php
$page = "Index";
$nav = array("Accueil" => "Index", "Produits" => "Products");
include_once('www/header.php');
?>
<div class="container first-div-index">
    <div id="map" style="height:56vw;"></div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Store</th>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<script>
    var cookie;
    var id = new RegExp('id=');

    var map = L.map('map').setView([38, -100], 4);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    $(document).ready(function() {
        $.get("https://dev-louveau222.users.info.unicaen.fr/bikestores/stores", function(data) {
            for (var i = 0; i < data.length; i++) {
                var store = data[i];
                trouveCoor(store);

            }
        });
        var locationTest = new RegExp('loc');
        if (!locationTest.test(document.cookie)) {
            var aujour = new Date();
            var date = new Date();
            date.setTime(aujour.getTime() + (60 * 60 * 1000));
            var expire = "; expires=" + date.toGMTString();
            if (window.confirm("Do you let us use your location to show you where you are?")) {
                document.cookie = "loc=true" + expire;
            } else {
                document.cookie = "loc=false" + expire;
            }
        }
        var locationAuto = new RegExp('loc=true');
        if (locationAuto.test(document.cookie)) {
            $.get("https://api.bigdatacloud.net/data/client-ip", function(data) {
                var ip = data.ipString;
                $.get("https://api.apibundle.io/ip-lookup?apikey=91c4df359b6748158577618a61d86e56&ip=" + ip, function(data) {
                    var position = L.icon({
                        iconUrl: "www/img/position.png",
                        iconSize: [25, 25]
                    })
                    var marker = L.marker([data.latitude, data.longitude], {
                        icon: position
                    }).addTo(map);
                    marker.bindPopup("Your position");
                });
            });
        }
        cookie = document.cookie;
        var inter = setInterval(checkCookie, 1000);
    });

    function checkCookie() {
        if (cookie != document.cookie) {
            deco('cookie modifié','red');
        }
    }

    function trouveCoor(store) {
        $.get("https://api.geoapify.com/v1/geocode/search?street=" + store.street + "&city=" + store.city + "&postcode=" + store.zip_code + "&apiKey=4479906f83b64c2ab3abcfe028008f93", function(result) {
            var coor = result.features[0].geometry.coordinates;
            var marker = L.marker([coor[1], coor[0]]).addTo(map);
            marker.bindPopup("<div class='d-flex justify-content-center flex-column align-items-center'><img style='width:80%' src=\"www/img/store" + store.store_id + ".jpg\"><h5><a class='text-decoration-none' href=\"store.php?id=" + store.store_id + "\">" + store.store_name + "</a></h5></div><p>" + store.street + ", " + store.city + ", " + store.zip_code + "</p><p>" + store.phone + "</p><p>" + store.email + "</p>");
            var tr = document.createElement("tr");
            tr.innerHTML = "<td scope=\"col\"><img style='width:15vw' src=\"www/img/store" + store.store_id + ".jpg\"></td><td scope=\"col\"><h5><a class='text-decoration-none' href=\"store.php?id=" + store.store_id + "\">" + store.store_name + "</a></h5></td><td scope=\"col\"><p>" + store.street + ", " + store.city + ", " + store.zip_code + "</p></td><td scope=\"col\"><p>" + store.phone + "</p></td><td scope=\"col\"><p>" + store.email + "</p></td>";
            $('table>tbody')[0].appendChild(tr);
        })
    }
</script>

<?php
include_once('www/footer.php');

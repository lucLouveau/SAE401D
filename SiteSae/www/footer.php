    </main>
    <footer class="bg-body-secondary d-flex justify-content-between rounded-top-5 ">
        <div class="d-flex flex-wrap align-items-center flex-column justify-content-center m-3" style="width:30%;">
            <p>Bikestore</p>
            <p>3700 Portola Drive, Santa Cruz, 95060</p>
            <p>(831) 476-4321</p>
            <p>bikestore@bikes.shop</p>
        </div>
        <div class="d-flex align-items-center flex-column justify-content-center m-3" style="width:30%;">
            <nav>
                <ul class="p-0 m-0 text-center">

                </ul>
            </nav>
            <script>
                var li = ($('.navbar-nav')[0].querySelectorAll('li'));
                for (var i = 0; i < li.length; i++) {
                    $(('footer nav>ul'))[0].innerHTML += li[i].innerHTML;
                }
                $(('footer nav>ul'))[0].innerHTML += "<a href='legal.php' class='nav-link'>Legal Notice</a>";
            </script>
        </div>
        <div class="d-flex align-items-center flex-column justify-content-center m-3" style="width:30%;">
            <p>All right reserved</p>
            <p>LucLouveau&copy;<?php echo date("Y"); ?></p>
        </div>
    </footer>
    </body>

    </html>
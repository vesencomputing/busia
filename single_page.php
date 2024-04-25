<?php
include_once 'header.php';
$id = security_get("id");
$sql = "select * from places where id = '$id'";
$row = select_rows($sql);
$item = $row[0];




?>
<style>
    #mymap2 {
        height: 500px;
        width: auto;
    }
</style>
<div class="container-xxl">
    <div class="row mt-4">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><?= $item['name'] ?></h4>
                            <p><?= $item['address'] ?></p>
                            <p><?= $item['phone'] ?></p>
                            <p>
                                <?= $item['description'] ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div id="mymap2"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<script>
    window.onload = () => {

        var lat = <?= $item['latitude'] ?>;
        var long = <?= $item['longitude'] ?>;

        var map = L.map('mymap2').setView([lat, long], 20);

        L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        var icmarker2 = L.AwesomeMarkers.icon({
            icon: '<?= $item['type'] ?>',
            prefix: 'fa',
            markerColor: 'blue'
        });

        L.marker([lat, long],{
            icon: icmarker2
        }).addTo(map)
            .bindPopup('<b><?= $item['name'] ?></b>')
            .openPopup();
    }
</script>
<?php
include_once 'footer.php';

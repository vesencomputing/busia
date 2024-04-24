<?php
require_once('model/operations.php');

include_once 'header.php';
?>
        <div class="container-xxl flex-grow-1 container-p-y">
                <div class = "row">
                    <div class = "col-md-10">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    Businesses
                                </h5>
                            </div>
                            <div class = "card-body">
                                <div id="mymap"></div>
                                <table class="table mt-3">
                                    <thead>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                    </thead>
                                    <?php
                                    $sql = "select * from places";
                                    $row = select_rows($sql);
                                    foreach ($row as $key=>$item){
                                        ?>
                                        <tr>
                                            <td><?php echo $item['name']; ?></td>
                                            <td><?php echo $item['address']; ?></td>
                                            <td><?php echo $item['phone']; ?></td>
                                            <td><a href="single_page.php?id=<?=$item['id']?>">

                                                    <i class="fa fa-eye"></i>
                                                </a> </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
        </div>
        <!-- / Content -->

<?php
include_once 'footer.php';

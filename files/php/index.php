<?php
include "config.php";
session_start();
if(!isset($_SESSION['user'] )){
    echo '<script>window.location.replace("login.php");</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- DATATABLES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <style>
        /* ALERT BOX STYLE */
        .alert-success {
            position: absolute;
            z-index: 99;
            top: 20px;
            left: 50%;
            background-color: #3e8e41;
            padding: 10px;
            border-radius: 6px;
            color: white;
            font-size: 12px;
        }

        .alert-warning {
            position: absolute;
            z-index: 99;
            top: 20px;
            left: 50%;
            background-color: #db4848;
            padding: 10px;
            border-radius: 6px;
            font-size: 12px;
            color: white;
        }
    </style>
</head>

<body>

    <!-- alert -->
    <?php
    if (isset($_SESSION["message-type"])) : ?>
        <div class="alert-content">
            <?php
            if ($_SESSION["message-type"] === "success") : ?>
                <div class="alert-<?= $_SESSION["message-type"] ?> alertBox">
                    <?php
                    echo $_SESSION["message"];
                    unset($_SESSION["message-type"]);
                    unset($_SESSION["message"]);
                    ?>

                </div>
            <?php
            elseif ($_SESSION["message-type"] === "warning") : ?>
                <div class="alert-<?= $_SESSION["message-type"] ?> alertBox">
                    <?php
                    echo $_SESSION["message"];
                    unset($_SESSION["message-type"]);
                    unset($_SESSION["message"]);
                    ?>
                </div>
            <?php endif; ?>
        </div>

    <?php
    endif;
    ?>

    <div class="container">
        <div class="btn-group" role="group" aria-label="Basic example" style="margin:0;padding-top:20px">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add</button>
            <a href="about.php" style="margin-left: 100px;margin-top:10px;">About Us</a>
            <a href="?logout" style="margin-left: 100px;margin-top:10px;">Logout</a>
        </div>
        <div class="content">
            <!-- data tables cant be affected by height, unless overflow! -->
            <table id="example" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Equipment ID</th>
                        <th>Serial Number</th>
                        <th>Name</th>
                        <th>Manufacturer</th>
                        <th>Model Number</th>
                        <th>Price</th>
                        <th>Warraty Expiration</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM equipment";
                    $result = $mysql->query($query);

                    if (mysqli_num_rows($result) > 0) :
                        while ($row = $result->fetch_assoc()) :
                    ?>
                            <tr>
                                <td><?= $row['equip_id'] ?></td>
                                <td><?= $row['serial_num'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['manufacturer'] ?></td>
                                <td><?= $row['model_num'] ?></td>
                                <td><?= $row['price'] ?></td>
                                <td><?= $row['warranty_exp'] ?></td>
                                <td style="width:150px">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editModal" onclick="editBTN(this)">Edit</button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="delBTN(this)">Delete</button>
                                </td>
                            </tr>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Equipment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="item-name">Serial Number</label>
                                <input type="text" class="form-control" id="serial" placeholder="Enter serial umber" name="serial">
                            </div>
                            <div class="form-group">
                                <label for="item-description">Equipment Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter equipment name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="item-name">Manufacturer</label>
                                <input type="text" class="form-control" id="manufacturer" placeholder="Manufacturer" name="manufacturer">
                            </div>
                            <div class="form-group">
                                <label for="item-description">Model Number</label>
                                <input type="text" class="form-control" id="model" placeholder="Model Number" name="model">
                            </div>
                            <div class="form-group">
                                <label for="item-name">Price</label>
                                <input type="text" class="form-control" id="price" placeholder="Item Price" name="price">
                            </div>
                            <div class="form-group">
                                <label for="item-description">Warranty Expiration</label>
                                <input type="date" class="form-control" id="warranty" placeholder="Warranty" name="warranty">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="addEQPT">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Edit Equipment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" class="form-control" id="equip-id"  name="equip-id">
                            <div class="form-group">
                                <label for="item-name">Serial Number</label>
                                <input type="text" class="form-control" id="equip-serial" placeholder="Enter serial number" name="equip-serial">
                            </div>
                            <div class="form-group">
                                <label for="item-description">Equipment Name</label>
                                <input type="text" class="form-control" id="equip-name" placeholder="Enter equipment name" name="equip-name">
                            </div>
                            <div class="form-group">
                                <label for="item-name">Manufacturer</label>
                                <input type="text" class="form-control" id="equip-manufacturer" placeholder="Manufacturer" name="equip-manufacturer">
                            </div>
                            <div class="form-group">
                                <label for="item-description">Model Number</label>
                                <input type="text" class="form-control" id="equip-model" placeholder="Model number" name="equip-model">
                            </div>
                            <div class="form-group">
                                <label for="item-name">Price</label>
                                <input type="text" class="form-control" id="equip-price" placeholder="Price" name="equip-price">
                            </div>
                            <div class="form-group">
                                <label for="item-description">Warranty Expiration</label>
                                <input type="date" class="form-control" id="equip-warranty" placeholder="Warranty" name="equip-warranty">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="editEQPT">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Delete Equipment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" id="id_" name="id_1">
                            <p> Are you sure you want to delete <b><i><span id="name_equip"></span></i></b> this item?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" name="delEQPT">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- PHP QUERIES -->
    <?php
    // EDIT
    if (isset($_POST['editEQPT'])) {
        $id = $_POST['equip-id'];
        $serial = $_POST['equip-serial'];
        $name = $_POST['equip-name'];
        $manufacturer = $_POST['equip-manufacturer'];
        $model = $_POST['equip-model'];
        $price = $_POST['equip-price'];
        $warranty = $_POST['equip-warranty'];
        // query
        $editQuery = "UPDATE equipment SET name = '$name', manufacturer = '$manufacturer', model_num = '$model', serial_num = '$serial', price = '$price', warranty_exp = '$warranty' WHERE equip_id = $id  ";
        // submitting query
        $getEdit = $mysql->query($editQuery);

        if ($getEdit === true) {
            $_SESSION["message-type"] = "success";
            $_SESSION["message"] = "Update successful!";
            echo '<meta http-equiv="refresh" content="0">';
        } else {
            $_SESSION["message-type"] = "warning";
            $_SESSION["message"] = "Update failed!";
            echo '<meta http-equiv="refresh" content="0">';
        }
        $mysql->close();
    }
    // ADD
    if (isset($_POST['addEQPT'])) {
        $add_serial = $_POST['serial'];
        $add_name = $_POST['name'];
        $add_manufacturer = $_POST['manufacturer'];
        $add_model = $_POST['model'];
        $add_price = $_POST['price'];
        $add_warranty = $_POST['warranty'];
        // add query
        $addQuery = "INSERT INTO equipment (name,manufacturer,model_num,serial_num,price,warranty_exp) 
        VALUES('$add_name','$add_manufacturer','$add_model','$add_serial','$add_price','$add_warranty') ";

        // submitting the query
        $getAdd = $mysql->query($addQuery);
        if ($getAdd === true) {
            $_SESSION["message-type"] = "success";
            $_SESSION["message"] = "Update successful!";
            echo '<meta http-equiv="refresh" content="0">';
        } else {
            $_SESSION["message-type"] = "warning";
            $_SESSION["message"] = "Update failed!";
            echo '<meta http-equiv="refresh" content="0">';
        }
        $mysql->close();
    }
    // DELETE
    if (isset($_POST['delEQPT'])) {
        $id_1 = $_POST['id_1'];

        // query
        $delQuery = "DELETE FROM equipment WHERE equip_id = $id_1 ";
        // submitting query
        $getDel = $mysql->query($delQuery);
        if ($getDel === true) {
            $_SESSION["message-type"] = "success";
            $_SESSION["message"] = "Update successful!";
            echo '<meta http-equiv="refresh" content="0">';
        } else {
            $_SESSION["message-type"] = "warning";
            $_SESSION["message"] = "Update failed!";
            echo '<meta http-equiv="refresh" content="0">';
        }
    }
    // logout
    if(isset($_GET['logout'])){
        session_destroy();
    }
    ?>


    <!-- DATA TABLES -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <!-- BOOSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>

</html>
<script>
    console.log("connected")
    $(document).ready(function() {
        $('#example').DataTable();
    });

    //edit button
    function editBTN(btn) {
        // Get the row that contains the clicked button
        const row = btn.closest("tr");
        console.log(row)
        // Get the data from the row
        let id = row.cells[0].textContent;
        let serial = row.cells[1].textContent;
        let name = row.cells[2].textContent;
        let manufacturer = row.cells[3].textContent;
        let model = row.cells[4].textContent;
        let price = row.cells[5].textContent;
        let warranty = row.cells[6].textContent;

        // transfeering the data in the modal
        $('#equip-id').val(id);
        $('#equip-serial').val(serial);
        $('#equip-name').val(name);
        $('#equip-manufacturer').val(manufacturer);
        $('#equip-model').val(model);
        $('#equip-price').val(price);
        $('#equip-warranty').val(warranty);


    }
    //deete button
    function delBTN(btn) {
        // Get the row that contains the clicked button
        const row = btn.closest("tr");
        console.log(row);
        // Get the data from the row
        let id = row.cells[0].textContent;
        let name = row.cells[2].textContent;
        $('#name_equip').text(name);
        $('#id_').val(id);
    }
    // alertbox animation
    setTimeout(() => {
        $(".alertBox").hide();
    }, 2000);
</script>
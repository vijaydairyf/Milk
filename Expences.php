<?php
session_start();
$user = $_SESSION['user'];
$name = $_SESSION['name'];
$pageurl = $_SERVER['REQUEST_URI'];
$prefix = $expences= $date=$timestamp=$total_unit=$total_amount=$unit=$rate=$sellername=$phoneno=$address=$remark=$id="";
$reduce_quantity = 0;
$prefix = "";
$location = $prefix . "index.php";
if (isset($_SESSION['user'])) {
    
} else {
    header("Location: $location");
    exit;
}
include_once $prefix . 'db.php';

if (isset($_POST['sellername_for_post'])) {
    $sellername_for_post = $_POST['sellername_for_post'];
    $sql = "SELECT * FROM `seller` where `id` = '$sellername_for_post' ";
    $result = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $phoneno = $row['phoneno'];
        $address = $row['address'];
    }
    ?>
    <div class="col-sm-6 form-group ">
        <input type="number" class="form-control" required="true" name="phoneno" tabindex="1" value="<?php echo $phoneno; ?>"readonly>
        <label for="phoneno">&nbsp; &nbsp;Contact Number</label>
    </div>
    <div class="col-sm-6 form-group">
        <textarea id="address" class="form-control" name="address" rows="1" style="resize:none;width:100%;"readonly ><?php echo $address; ?></textarea>
        <label for="address">&nbsp; &nbsp;Address</label>
    </div>
    <?php
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM `ex` where`id` = '$id'";
    $result = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $expences = $row['expences'];
        $date = $row['date'];
        $timestamp = date("d-m-Y", strtotime($date));
        $total_unit = $row['total_unit'];
        $total_amount = $row['total_amount'];
        $unit = $row['unit'];
//        $balance = $_row['balance'];
        $rate = $row['rate'];
        $sellername = $row['sellername'];
        $phoneno = $row['phoneno'];
        $address = $row['address'];
        $remark = $row['remark'];
    }
}
$sql1 = "SELECT `id`, `expences` FROM `expense`";
$result = mysqli_query($mysqli, $sql1);
while ($row = mysqli_fetch_assoc($result)) {
    $get_expences[$row['id']] = $row['expences'];
// echo $get_expences[$row['expences']];exit;
}
$sqlc1 = "SELECT `id`, `name` FROM `seller`";
$result = mysqli_query($mysqli, $sqlc1);
while ($row = mysqli_fetch_assoc($result)) {
    $get_bname[$row['id']] = $row['name'];
    // echo $get_bname[$row['name']];exit;
}
$sqlc1 = "SELECT `id`, `unit` FROM `unit`";
$result = mysqli_query($mysqli, $sqlc1);
while ($row = mysqli_fetch_assoc($result)) {
    $get_uname[$row['id']] = $row['unit'];
    // echo $get_uname[$row['unit']];exit;
}

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}
//cho 'hhkhljljl';exit;
if (isset($_POST['save'])) {

    $expences = $_POST['expences'];
    $date1 = $_POST['date'];
    $date = new DateTime($date1);
    $timestamp = $date->format('Y-m-d'); // 31.07.2012
    $total_unit = $_POST['total_unit'];
    $unit = $_POST['unit'];
//    $balance = $_POST['balance'];
    $rate = $_POST['rate'];
    $total_amount = $_POST['total_amount'];
    $buyername = $_POST['buyername'];
    $phoneno = $_POST['phoneno'];
    $address = $_POST['address'];
    $remark = $_POST['remark'];


    if ($id) {

        $sql = " UPDATE `ex` SET `expences`='$expences',`date`='$timestamp',`sellername`='$buyername',`phoneno`='$phoneno',`address`='$address',`total_unit`='$total_unit',`total_amount`='$total_amount',`rate`='$rate',`unit`='$unit',`remark`='$remark' WHERE id='$id'";
        $result = mysqli_query($mysqli, $sql) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($mysqli));
        header("Location: Expences.php?msg=3");
        exit;
    } else {
        $sql = "INSERT INTO `ex`(`expences`, `date`,`sellername`,`phoneno`,`address`,`total_unit`,`total_amount`, `unit`,`rate`,`remark`) VALUES('$expences','$timestamp','$buyername','$phoneno','$address','$total_unit','$total_amount','$unit','$rate','$remark')";
//        echo $sql;exit;
        $result = mysqli_query($mysqli, $sql) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($mysqli));
        header("Location: Expences.php?msg=2");
        exit;
    }
}
if (isset($_GET['operation']) && $_GET['operation'] == 'delete') {
    $sql = "DELETE FROM `ex` where `id` = '$id' ";
    $result = mysqli_query($mysqli, $sql);
    $affected_rows = mysqli_affected_rows($mysqli);
    if ($affected_rows > 0) {
        $msg = "4";
    } else {
        $msg = "2";
    }
    header('Location: Expences.php?msg=' . $msg);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Milk Management - Expenses</title>
    <link rel="shortcut icon" type="image/png" href="assets/img/144.png"/>
    <?php include_once 'include/headtag.php'; ?>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>     
</head>
<body class="menubar-hoverable header-fixed ">
    <!-- BEGIN HEADER-->
    <?php include_once 'include/header.php'; ?>
    <!-- END HEADER-->

    <!-- END BASE -->

    <div id="base">
        <div class="offcanvas">  </div>
        <div id="content">

            <section>
                <div class="section-body contain-lg">
                    <div class="row"><!--end .col -->
                        <div class=" col-lg-offset-2 col-md-8 col-sm-10">

                            <div class="card">
                                <div class="card-head style-primary">
                                    <header>Expense (Payment Issue)</header>
                                </div>
                                <div class="card-body">
                                    <form class="form form-validate" role="form" method="POST">


                                        <div class="row">
                                            <div class = "col-sm-6 form-group ">
                                                <select name="expences" id="breedtype" tabindex="1" class="form-control js-example-basic-single" required="<?php echo $expences; ?>">
                                                    <option value="">Please Select Expense</option>
                                                    <?php
                                                    $sql = "select * from expense ORDER BY id DESC";
                                                    $result = mysqli_query($mysqli, $sql);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php
                                                        if ($row['id'] == $expences) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $row['expences']; ?></option><?php } ?>
                                                    </select>

                                                    <label for="expences">&nbsp;&nbsp; Select Expenses <sup style="color:red;">*</sup></label>
                                                </div>
                                                <div class="col-sm-6 form-group ">
                                                    <!-- <div class="input-group date" id="demo-date" > -->
<!--                                                    <input type="date" class="form-control" required="true" name="date" max = "<?php echo date('Y-m-d'); ?>" tabindex="1" value="<?php echo $date; ?>">
    <label for="date">&nbsp; &nbsp; Date<sup style="color:red;">*</sup></label>-->
    <label for="dob">&nbsp; &nbsp;Date <sup style="color:red;">*</sup></label>
    <div class="form-group control-width-normal">
        <div class="input-group date" id="demo-date">
            <input type="text" class="form-control" required="true" name="date"value="<?php
            if (!$id) {
                echo date('d-m-Y');
                } else {
                    echo $timestamp;
                }
                ?>">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div><!--end .form-group -->
    </div>
</div>
<div class="row">
    <div class="col-sm-6 form-group">
        <input type="number" class="form-control" min="1" required="true" name="total_unit" id="total_unit" tabindex="1" value="<?php echo $total_unit; ?>">
        <label for="rate">&nbsp; &nbsp; Total Unit <sup style="color:red;">*</sup></label>
    </div>
                                                <!--                                                 <div class="col-sm-6 form-group ">
                                                                                                    <input type="number" class="form-control" min="1" id="total_unit" required="true" name="total_unit" tabindex="1" value="<?php echo $total_unit; ?>">
                                                                                                    <label for="total_unit">&nbsp; &nbsp;Total Milk (liter)<sup style="color:red;">*</sup></label>
                                                                                                </div>-->
                                                                                                <div class = "col-sm-6 form-group ">
                                                                                                    <select name="buyername" id="sellername" tabindex="1" class="form-control js-example-basic-single"> <?php echo $sellername; ?>
                                                                                                    <option value="">Please Select Seller Name</option>
                                                                                                    <?php
                                                                                                    $sql = "select `id`,`name` from seller";
                                                                                                    $result = mysqli_query($mysqli, $sql);
                                                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                                                        $id1 = $row['id'];
                                                                                                        ?>
                                                                                                        <option value="<?php echo $row['id'] ?>"<?php
                                                                                                        if ($sellername == $id1) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>><?php echo $row['name']; ?></option>
                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                                <label for="name">&nbsp; &nbsp;Seller Name </label>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row" id= "total">
                                                                                            <div class="col-sm-4 form-group ">
                                                                                                <input type="text" name="rate" required="true" id="price" min="1" tabindex="1" value="<?php echo $rate; ?>"class="form-control">
                                                                                                <label for="rate">&nbsp; &nbsp;Price (Per unit)<sup style="color:red;">*</sup></label>
                                                                                            </div>
                                                                                            <div class="col-sm-2 form-group ">
                                                                                                <select name="unit" id="unit" tabindex="1"  required="true" class="form-control js-example-basic-single"> <?php echo $unit; ?>
                                                                                                <option value="">Select unit</option>
                                                                                                <?php
                                                                                                $sql = "select `id`,`unit` from unit";
                                                                                                $result = mysqli_query($mysqli, $sql);
                                                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                                                    $id1 = $row['id'];
                                                                                                    ?>
                                                                                                    <option value="<?php echo $row['id'] ?>"<?php
                                                                                                    if ($unit == $id1) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>><?php echo $row['unit']; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                            <label for="rate">&nbsp; &nbsp;select Unit<sup style="color:red;">*</sup></label>
                                                                                        </div>

                                                                                        <div class="col-sm-6 form-group ">
                                                                                            <input readonly type="number" id="total_amount" min="1" class="form-control" required="true" name="total_amount" tabindex="1" value="<?php echo $total_amount; ?>">
                                                                                            <label for="total_amount">&nbsp; &nbsp;Total Amount</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row" id="buyer_detail">
                                                                                        <div class="col-sm-6 form-group ">
                                                                                            <input type="number" class="form-control" required="true" name="phoneno" tabindex="1" value="<?php echo $phoneno; ?>"readonly>
                                                                                            <label for="phoneno">&nbsp; &nbsp;Contact Number</label>
                                                                                        </div>
                                                                                        <div class="col-sm-6 form-group">
                                                                                            <textarea id="address" class="form-control" name="address" rows="1" style="resize:none;width:100%;"readonly><?php echo $address; ?></textarea>
                                                                                            <label for="address">&nbsp; &nbsp;Address</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-sm-12 form-group">
                                                                                            <textarea id="remark" class="form-control" name="remark" rows="5" style="resize:none;width:100%;" ><?php echo $remark; ?></textarea>
                                                                                            <label for="remark">&nbsp; &nbsp;Remark</label>

                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="card-actionbar">
                                                                                        <div class="card-actionbar-row">
                                                                                            <div class="row text-right">
                                                                                                <div class="col-md-12">
                                                                                                    <button type="submit" class="btn ink-reaction btn-raised btn-primary" tabindex="1" name="save">Submit</button>
                                                                                                    <div class="col-md-2">
                                                                                                        <?php if ($id) { ?>
                                                                                                            <!--<button type="reset"  class="btn ink-reaction btn-flat btn-primary">Cancel</button>-->
                                                                                                            <button onclick="goBack()">Cancel</button>
                                                                                                        <?php } else {
                                                                                                            ?>
                                                                                                            <button type="reset"  class="btn ink-reaction btn-flat btn-primary">Reset</button>
                                                                                                        <?php } ?>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form></div></div></div></div></div></section>

                                                                                    <section>
                                                                                        <div class="section-body contain-lg">
                                                                                            <div class="row"><!--end .col -->
                                                                                                <div class=" col-md-12 col-sm-12">
                                                                                                    <!--<h2 class="text-primary">Please Fill the Details</h2>-->
                                                                                                    <div class="card">
                                                                                                        <div class="card-head style-primary">
                                                                                                            <header>Expenses Details</header>
                                                                                                        </div>
                                                                                                        <div class="card-body">
                                                                                                            <form class="form  form-validate" role="form" method="POST">

                                                                                                                <div id="regwise_select" class="tbgetsect">
                                                                                                                    <table id="datatable1" class="table diagnosis_list">
                                                                                                                        <thead>
                                                                                                                            <tr>                                    
                                                                                                                                <th>SlNo</th>
                                                                                                                                <th>Action</th>
                                                                                                                                <th>Expenses</th>
                                                                                                                                <th>Date</th>
                                                                                                                                <th>Total Unit</th>
                                                                                                                                <th>Unit</th>
                                                                                                                                <th>Rate per Unit </th>
                                                                                                                                <th>Total Amount</th>
                                                                                                                                <th>Seller</th>
                                                                                                                                <th>Phone No</th>
                                                                                                                                <th>Address</th>  
                                                                                                                                <th>Remark</th>
                                                                                                                            </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody class="ui-sortable" >
                                                                                                                            <?php
                                                                                                                            $i = 1;
                                                                                                                            $sql = "select * from ex  ORDER BY `id` DESC";
                                                                                                                            $result = mysqli_query($mysqli, $sql);
                                                                                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                                                                                $id = $row['id'];
                                                                                                                                ?>
                                                                                                                                <tr  id="<?php echo $row['id']; ?>"  >
                                                                                                                                    <td><?php echo $i; ?></td>
                                                                                                                                    <td class="text-left">   
                                                                                                                                        <a href="Expences.php?id=<?php echo $id; ?>"><button type="button" class="btn ink-reaction btn-floating-action btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Edit row"><i class="fa fa-pencil"></i></button></a>
                                                                                                                                        <!--<a href="form_view.php?f_id=<?php echo $id; ?>" target="_blank"><button type="button" class="btn ink-reaction btn-floating-action btn-info" data-toggle="modal" data-target="#modal-publish<?php echo $id; ?>" data-placement="top" data-original-title="View row"><i class="fa fa-fw fa-eye"></i></button></a>-->                                          
                                                                                                                                        <a href="Expences.php?id=<?php echo $id; ?>&operation=delete"><button  style="margin-bottom: 3px;" type="button" class="btn ink-reaction btn-floating-action btn-danger"    data-toggle="tooltip" onclick="return confirm('Are you sure to delete?')" data-placement="top" data-original-title="Delete row"><i class="fa fa-trash"></i></button></a>
                                                                                                                                    </td>
                                                                                                                                    <td><?php echo $get_expences[$row['expences']]; ?></td>
                                                                                                                                    <td><?php echo date('d/m/Y', strtotime($row['date'])); ?></td>
                                                                                                                                    <td><?php echo $row['total_unit']; ?></td>
                                                                                                                                    <td><?php echo $get_uname[$row['unit']]; ?></td>
                                                                                                                                    <td><?php echo $row['rate']; ?></td>
                                                                                                                                    <td><?php echo $row['total_amount']; ?></td>
                                                                                                                                    <td><?php echo $get_bname[$row['sellername']]; ?></td>
                                                                                                                                    <td><?php echo $row['phoneno']; ?></td>
                                                                                                                                    <td><?php echo $row['address']; ?></td>
                                                                                                                                    <td><?php echo $row['remark']; ?></td>
                                                                                                                                </tr>  
                                                                                                                                <?php
                                                                                                                                $i++;
                                                                                                                            }
                                                                                                                            ?>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                            </form>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </section>
                                                                                </div>
                                                                            </div>


                                                                            <?php include_once 'include/menubar.php'; ?>
                                                                            <?php include_once 'include/jsfiles.php'; ?>
                                                                            <script>
                                                                                $("#demo-date").datepicker({
                                                                                    format: 'dd-mm-yyyy',
                                                                                    startDate: '1-3-2019',
                                                                                    endDate: '+0d',
                                                                                });
                                                                            </script>
                                                                            <script>
                                                                                <?php if ($msg == '2') { ?>
                                                                                    Command: toastr["success"]("Entry Added  sucesssfully", "Sucesss")
                                                                                <?php } elseif ($msg == '1') {
                                                                                    ?>
                                                                                    Command: toastr["error"]("Some Error exist", "Error")
                                                                                <?php } elseif ($msg == '3') { ?>
                                                                                    Command: toastr["success"]("Entry Updated Sucesssfully", "Sucesss")
                                                                                <?php } elseif ($msg == '4') { ?>
                                                                                    Command: toastr["success"]("Entry Deleted Sucesssfully", "Sucesss")
                                                                                <?php } ?>
                                                                            </script>
                                                                            <script>
                                                                                $('#sellername').change(function () {
                                                                                    var sellername_variable = $(this).val();
                                                                                    $.post("Expences.php",
                                                                                    {
                                                                                        sellername_for_post: sellername_variable,
                                                                                    },
                                                                                    function (data, status) {
                            //alert("Data: " + data + "\nStatus: " + status);
                            $('#buyer_detail').html(data);
                        });
                                                                                });

                                                                            </script>
                                                                            <script>
                                                                                function total_amount()
                                                                                {
                                                                                    var total_unit = $("#total_unit").val();
                                                                                    var rate = $("#price").val();
                                                                                    var total_amount = parseInt(total_unit) * parseInt(rate);
                                                                                    $("#total_amount").val(total_amount);
                                                                                }

                                                                                $("#total_unit").keyup(function () {
                                                                                    total_amount();
                                                                                });
                                                                                $("#total_unit").change(function () {
                                                                                    total_amount();
                                                                                });
                                                                                document.getElementById("total_unit").addEventListener("wheel", total_amount);

                                                                                $("#price").change(function () {
                                                                                    total_amount();
                                                                                });

                                                                                $("#price").keyup(function () {
                                                                                    total_amount();
                                                                                });
                                                                                $("#price").change(function () {
                                                                                    total_amount();
                                                                                });
                                                                                document.getElementById("price").addEventListener("wheel", total_amount);

                                                                                $("#price").change(function () {
                                                                                    total_amount();
                                                                                });
                                                                            </script>
                                                                            <script>
                                                                                function goBack() {
                                                                                    window.history.back();
                                                                                }
                                                                            </script>
                                                                        </body>
                                                                        </html>


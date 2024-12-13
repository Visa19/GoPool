<?php
session_start();
error_reporting(0);

include 'classes/DbConnector.php';

try {
    $dbConnector = new \classes\DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

$email = $_SESSION['login'];

$sql = "SELECT FullName FROM tblusers WHERE EmailId = :email";
$query = $dbh->prepare($sql);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
if ($results) {
    foreach ($results as $result) {
$postedby = $result->FullName; }}

if(isset($_POST['submit'])) {
    $vehicletitle = $_POST['vehicletitle'];
    $brand = $_POST['brand'];
    $vehicleoverview = $_POST['vehicalorcview'];
    $numberplate = $_POST['vehicle_num'];
    $fueltype = $_POST['fueltype'];
    $modelyear = $_POST['modelyear'];
    $seatingcapacity = $_POST['seatingcapacity'];
    $vimage1 = $_FILES["img1"]["name"];
    $vimage2 = $_FILES["img2"]["name"];
    $vimage3 = $_FILES["img3"]["name"];
    $vimage4 = $_FILES["img4"]["name"];
    $vimage5 = $_FILES["img5"]["name"];
    $airconditioner = $_POST['airconditioner'];
    $powerdoorlocks = $_POST['powerdoorlocks'];
    $antilockbrakingsys = $_POST['antilockbrakingsys'];
    $brakeassist = $_POST['brakeassist'];
    $powersteering = $_POST['powersteering'];
    $driverairbag = $_POST['driverairbag'];
    $passengerairbag = $_POST['passengerairbag'];
    $powerwindow = $_POST['powerwindow'];
    $cdplayer = $_POST['cdplayer'];
    $centrallocking = $_POST['centrallocking'];
    $crashcensor = $_POST['crashcensor'];
    $leatherseats = $_POST['leatherseats'];
    
    
  
    move_uploaded_file($_FILES["img1"]["tmp_name"], "admin/img/Veh_share_img/" . $_FILES["img1"]["name"]);
    move_uploaded_file($_FILES["img2"]["tmp_name"], "admin/img/Veh_share_img/" . $_FILES["img2"]["name"]);
    move_uploaded_file($_FILES["img3"]["tmp_name"], "admin/img/Veh_share_img/" . $_FILES["img3"]["name"]);
    move_uploaded_file($_FILES["img4"]["tmp_name"], "admin/img/Veh_share_img/" . $_FILES["img4"]["name"]);
    move_uploaded_file($_FILES["img5"]["tmp_name"], "admin/img/Veh_share_img/" . $_FILES["img5"]["name"]);

    $sql = "INSERT INTO tblrideshare_vehicles (VehiclesTitle, VehiclesBrand, VehiclesOverview, VehicleNumber, FuelType, ModelYear, SeatingCapacity, Vimage1, Vimage2, Vimage3, Vimage4, Vimage5, AirConditioner, PowerDoorLocks, AntiLockBrakingSystem, BrakeAssist, PowerSteering, DriverAirbag, PassengerAirbag, PowerWindows, CDPlayer, CentralLocking, CrashSensor, LeatherSeats,PostedBy) 
            VALUES (:vehicletitle, :brand, :vehicleoverview, :vehicle_num, :fueltype, :modelyear, :seatingcapacity, :vimage1, :vimage2, :vimage3, :vimage4, :vimage5, :airconditioner, :powerdoorlocks, :antilockbrakingsys, :brakeassist, :powersteering, :driverairbag, :passengerairbag, :powerwindow, :cdplayer, :centrallocking, :crashcensor, :leatherseats,:postedby)";
    
    $query = $dbh->prepare($sql);
    $query->bindParam(':vehicletitle', $vehicletitle, PDO::PARAM_STR);
    $query->bindParam(':brand', $brand, PDO::PARAM_STR);
    $query->bindParam(':vehicleoverview', $vehicleoverview, PDO::PARAM_STR);
    $query->bindParam(':vehicle_num', $numberplate, PDO::PARAM_STR);
    $query->bindParam(':fueltype', $fueltype, PDO::PARAM_STR);
    $query->bindParam(':modelyear', $modelyear, PDO::PARAM_STR);
    $query->bindParam(':seatingcapacity', $seatingcapacity, PDO::PARAM_STR);
    $query->bindParam(':vimage1', $vimage1, PDO::PARAM_STR);
    $query->bindParam(':vimage2', $vimage2, PDO::PARAM_STR);
    $query->bindParam(':vimage3', $vimage3, PDO::PARAM_STR);
    $query->bindParam(':vimage4', $vimage4, PDO::PARAM_STR);
    $query->bindParam(':vimage5', $vimage5, PDO::PARAM_STR);
    $query->bindParam(':airconditioner', $airconditioner, PDO::PARAM_STR);
    $query->bindParam(':powerdoorlocks', $powerdoorlocks, PDO::PARAM_STR);
    $query->bindParam(':antilockbrakingsys', $antilockbrakingsys, PDO::PARAM_STR);
    $query->bindParam(':brakeassist', $brakeassist, PDO::PARAM_STR);
    $query->bindParam(':powersteering', $powersteering, PDO::PARAM_STR);
    $query->bindParam(':driverairbag', $driverairbag, PDO::PARAM_STR);
    $query->bindParam(':passengerairbag', $passengerairbag, PDO::PARAM_STR);
    $query->bindParam(':powerwindow', $powerwindow, PDO::PARAM_STR);
    $query->bindParam(':cdplayer', $cdplayer, PDO::PARAM_STR);
    $query->bindParam(':centrallocking', $centrallocking, PDO::PARAM_STR);
    $query->bindParam(':crashcensor', $crashcensor, PDO::PARAM_STR);
    $query->bindParam(':leatherseats', $leatherseats, PDO::PARAM_STR);
     $query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
    $query->execute();
    
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId) {
        $msg = "Vehicle posted successfully for ride sharing.Enjoy your trip.";
    } else {
        $error = "Something went wrong. Please try again";
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">


	<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>GoPool | Ride Share Post Vehicle</title>

    <link rel="stylesheet" href="admin/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="admin/css/bootstrap-social.css">
    <link rel="stylesheet" href="admin/css/bootstrap-select.css">
    <link rel="stylesheet" href="admin/css/fileinput.min.css">
    <link rel="stylesheet" href="admin/css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="admin/css/style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">
					
						<h2 class="page-title">Register Vehicle For Ride Share</h2>

						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Basic Info</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data">
<div class="form-group">
<label class="col-sm-2 control-label">Vehicle Title<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="vehicletitle" class="form-control" required>
</div>
<label class="col-sm-2 control-label">Vehicle Brand<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" class="form-control" name="brand" placeholder="Eg:BMW" required>

</div>
</div>
											
<div class="hr-dashed"></div>
<div class="form-group">
<label class="col-sm-2 control-label">Vehical Overview<span style="color:red">*</span></label>
<div class="col-sm-10">
<textarea class="form-control" name="vehicalorcview" rows="3" required></textarea>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Vehicle Number On Plate<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="vehicle_num" class="form-control" required>
</div>
<label class="col-sm-2 control-label">Seating Capacity<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="seatingcapacity" class="form-control" placeholder="Eg:5" required>
</div>
<div class="hr-dashed"></div>
<br>
<br>
<label class="col-sm-2 control-label">Model Year <span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="modelyear" class="form-control" required>
</div>
<label class="col-sm-2 control-label">Select Fuel Type<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="fueltype" placeholder="Eg:Petrol" class="form-control" required>
</div>
</div>
</div>
<div class="form-group">
<div class="col-sm-12">
<h4><b>Upload Images Of Your Car</b></h4>
</div>
</div>


<div class="form-group">
<div class="col-sm-4">
Front View <span style="color:red">*</span><input type="file" name="img1" required>
</div>
<div class="col-sm-4">
Back View<span style="color:red">*</span><input type="file" name="img2" required>
</div>
<div class="col-sm-4">
Interior 1<span style="color:red">*</span><input type="file" name="img3" required>
</div>
</div>


<div class="form-group">
<div class="col-sm-4">
Interior 2<span style="color:red">*</span><input type="file" name="img4" required>
</div>
<div class="col-sm-4">
Any Side<input type="file" name="img5">
</div>

</div>
<div class="hr-dashed"></div>									
</div>
</div>
</div>
</div>
							

<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading">Accessories Of Your Car</div>
<div class="panel-body">


<div class="form-group">
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="airconditioner" name="airconditioner" value="1">
<label for="airconditioner"> Air Conditioner </label>
</div>
</div>
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="powerdoorlocks" name="powerdoorlocks" value="1">
<label for="powerdoorlocks"> Power Door Locks </label>
</div></div>
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="antilockbrakingsys" name="antilockbrakingsys" value="1">
<label for="antilockbrakingsys"> AntiLock Braking System </label>
</div></div>
<div class="checkbox checkbox-inline">
<input type="checkbox" id="brakeassist" name="brakeassist" value="1">
<label for="brakeassist"> Brake Assist </label>
</div>
</div>



<div class="form-group">
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="powersteering" name="powersteering" value="1">
<input type="checkbox" id="powersteering" name="powersteering" value="1">
<label for="inlineCheckbox5"> Power Steering </label>
</div>
</div>
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="driverairbag" name="driverairbag" value="1">
<label for="driverairbag">Driver Airbag</label>
</div>
</div>
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="passengerairbag" name="passengerairbag" value="1">
<label for="passengerairbag"> Passenger Airbag </label>
</div></div>
<div class="checkbox checkbox-inline">
<input type="checkbox" id="powerwindow" name="powerwindow" value="1">
<label for="powerwindow"> Power Windows </label>
</div>
</div>


<div class="form-group">
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="cdplayer" name="cdplayer" value="1">
<label for="cdplayer"> CD Player </label>
</div>
</div>
<div class="col-sm-3">
<div class="checkbox h checkbox-inline">
<input type="checkbox" id="centrallocking" name="centrallocking" value="1">
<label for="centrallocking">Central Locking</label>
</div></div>
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="crashcensor" name="crashcensor" value="1">
<label for="crashcensor"> Crash Sensor </label>
</div></div>
<div class="col-sm-3">
<div class="checkbox checkbox-inline">
<input type="checkbox" id="leatherseats" name="leatherseats" value="1">
<label for="leatherseats"> Leather Seats </label>
</div>
</div>
</div>

    <br>
    <br>


											
												
	<?php if ($_SESSION['login']) { ?>
    <button class="btn btn-primary" name="submit" type="submit">Post Vehicle Now</button>
<?php } else { ?>
    <a href="#loginform" class="btn btn-primary" data-toggle="modal" data-dismiss="modal">Login For Posting Vehicle</a>
<?php } ?>
												
											

										</form>
									</div>
								</div>
							</div>
						</div>
						
					
`
					</div>
				</div>
				
			

			</div>
		</div>
	</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<script src="assets/switcher/js/switcher.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>
<?php include('includes/footer.php');?>
</body>
</html>

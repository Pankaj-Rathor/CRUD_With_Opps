
<?php
require 'Database.php';
$op = new Operation();

$nameErr = $ageErr = '*';
if(isset($_GET['type']) && $_GET['type']=='delete') {
	$id = $op->get_safe_str($_GET['id']);

	$op->deleteData("user",$id);
}

if(isset($_GET['name']) && isset($_GET['age']) && $_GET['submit']=='Add') {
	$name = $op->get_safe_str($_GET['name']);
	$age = $op->get_safe_str($_GET['age']);
	$submit = true;
	if(empty($name)){
		$nameErr = "*Name is required";
		$submit = false;
	}
	if(empty($age)){
		$ageErr = "*Age is required";
		$submit = false;
	}
	if($age<18){
		$ageErr = "*You are under 18";
		$submit = false;
	}
	if ($submit) {
		$addUser = array("name"=>$name,"age"=>$age);
		$op->insertData("user",$addUser);
	}
	
}

$id = $editName = $editAge = "";
$action = "Add";
if(isset($_GET['type']) && $_GET['type'] == 'edit'){
	$id = $op->get_safe_str($_GET['id']);

	$row = $op->selectData("user",'*',["id"=>$id]);
	foreach ($row as $value) {
		$editName = $value['name'];
		$editAge = $value['age'];
	}
	$action = "Edit";
}

//Update User
if(isset($_GET['id']) && isset($_GET['name']) && isset($_GET['age']) && $_GET['submit']=='Edit') {
	$id = $op->get_safe_str($_GET['id']);
	$name = $op->get_safe_str($_GET['name']);
	$age = $op->get_safe_str($_GET['age']);
	$submit = true;
	if(empty($name)){
		$nameErr = "*Name is required";
		$submit = false;
	}
	if(empty($age)){
		$ageErr = "*Age is required";
		$submit = false;
	}
	if($age<18){
		$ageErr = "*You are under 18 age";
		$submit = false;
	}
	if ($submit) {
		$updateUser = array("name"=>$name,"age"=>$age);
		$op->updateData("user",$id,$updateUser);
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CRUD_With_Opps</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
	<style type="text/css">
	*{
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}
	li a{
		text-decoration: none;
		color: white;
	}
	li{
		display: none;
		border-bottom: 1px solid dimgray;
	}

	ul:hover li{
		display: block;
	}
	li:hover{
		background: deepskyblue;
	}
	li a:hover{
		text-decoration: none;
		color: white;
	}
	ul,li{
		padding-left: 5px !important;
		padding-right: 5px !important;
	}
</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 mb-3"> <h2 class="text-center btn-danger pb-1 ">CRUD WITH OPPS</h2> </div>
			<div class="col-md-12">
				<div class="col-md-12"><h4 class="btn-success pt-1 pb-1 text-center"> USERS MANAGEMENT
					
				</h4>
			</div>
			<div class="row">
				<div class="col-md-5 ml-3">
					<h4 class="text-center btn-primary pb-1">Add User</h4>
					<form action="">
						<input type="hidden" name="id" value="<?php echo $id?>">
						<div class="form-group">
							<label for="name">Name</label> <i style="color: red;"><?php echo $nameErr?></i>
							<input class="form-control" type="text" name="name" placeholder="Enter Name..." value="<?php echo $editName?>">
						</div>
						<div class="form-group">
							<label for="age">Age</label> <i style="color: red;"><?php echo $ageErr?></i>
							<input class="form-control" type="text" name="age" placeholder="Enter Age..." value="<?php echo $editAge?>">
						</div>
						<div class="form-group">
							<input class="form-control btn btn-dark" type="submit" name="submit" value="<?php echo $action?>">
						</div>
					</form>
				</div>
				<div class="col-md-6 ml-3">
					<table class="table text-center">
						<tr class="btn-primary">
							<th>ID</th>
							<th>NAME</th>
							<th>AGE</th>
							<th>ACTION</th>
						</tr>
						<?php
						$page = 0;
						$prev = 0;
							$i=1; //Row Number
							// $last_i = 0;
							if(isset($_GET['next'])){
								$page = (int)$_GET['next'];
								$prev = $page;
								// $i+=5;
							}
							if(isset($_GET['prev'])){
								if((int)$_GET['prev']<0){
									$page = 0;
								}else{
									$prev = (int)$_GET['prev'];
									$page = $prev;
								}								
							}

							//order by 
							$order = "id";
							$sort = "desc";
							if(isset($_GET['order'])){
								$order = $_GET['order'];
							}
							if(isset($_GET['sort'])){
								$sort = $_GET['sort'];
							}
							$data = $op->selectData("user","*","",$order,$sort,5,$page);
						// echo"<pre>";
						// print_r($data);
							if(isset($data[0])){
								$i += $page; 
								foreach ($data as $value) {
									?>
									<tr>
										<td><?php echo $i?></td>
										<td style="text-transform: uppercase;"><?php echo $value['name']?></td>
										<td><?php echo $value['age']?></td>
										<td>
											<a href="?type=edit&id=<?php echo $value['id']?>" class="btn btn-outline-primary">Edit</a>
											<a href="?type=delete&id=<?php echo $value['id']?>" class="btn btn-outline-danger">delete</a>
										</td>
									</tr>
									<?php
									$i++;
								}
							}
							?>
						</table>
						<hr style="background-color:red;">
						<div class="col-md-12">

							<a href="?next=<?php echo $page+=5;?>" class="btn btn-success" style="float:right; margin-right: 11%;">Next</a> 
							<a href="?prev=<?php echo $prev-=5;?>" class="btn btn-success" style="float:right; margin-right: 2%;">Prev</a>

							<ul class="btn btn-dark mr-5" name="order" style="float: right; color: white;">Order By DESC
								<li><a href="?order=id">Id</a></li>
								<li><a href="?order=name">Name</a></li>
								<li><a href="?order=age">Age</a></li>
							</ul>

							<ul class="btn btn-dark mr-4" name="order" style="float: right; color: white;">Order By ASC
								<li><a href="?order=id&sort=asc">Id</a></li>
								<li><a href="?order=name&sort=asc">Name</a></li>
								<li><a href="?order=age&sort=asc">Age</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
<?php

$servername = "localhost";
$username = "root";
$password = "Fathaah@97";
$database = "online_leave";

$con = mysqli_connect("$servername", "$username", "$password", "$database") or die("The page is temporarily down. Please try later.");

$current_time = date("h:i:s", time());

// temporary variables, will be replaced once on the server
$_SESSION['id'] = "f20150565"; // will be assigned in the actual site
$name = "A MOHAMED FATHAAHUL HUQ"; // can be extracted from users table
$full_id = "2015B5A40565H";  // can be extracted from users table

// if condition for checking if the form has been submitted and that they have submitted with some date (Bootstrap helps us, but we are just covering all bases)
if(isset($_POST['submit']) && isset($_POST['leave_date_in'])) {

  // we'll set all POST variables to a standard variable. Helpful when we have to make some change, just edit at one place.
  $leave_date_in = date('Y-m-d', strtotime($_POST['leave_date_in'])); 

  // we'll set out date only if the outstation period is for a range of days 
  if(isset($_POST['leave_date_out'])) { $leave_date_out = $_POST['leave_date_out']; } else { $leave_date_out = $leave_date_in; }

  // if condition to check if the input dates are greater than the present day.
  if(strtotime($leave_date_in) > time() || strtotime($leave_date_in) > time()) {

    // fetch all previous outstation request for that student.
    $query_outstation_data = mysqli_query($con, "SELECT * FROM `main` WHERE `uid`='{$_SESSION['id']}'");

    // counter variable for the array indexing
    $i = 0;

    // check if the student has previous outstations.
    if(mysqli_num_rows($query_outstation_data) != 0) {

      // iterate over the rows of data we just fecthed above.
      while($row = mysqli_fetch_assoc($query_outstation_data)) {

        // we will take the dates only after this moment unless we live in a world where time travel exists.
        if(strtotime($row['leave_date']) > time()) {

          // form the multidimensional array of all the data
          $outstation_data[$i++] = $row;
     
        }

      }

      // array declaration
      $unix_dates_array = array();
      $updated_outstation_data = array();

      // counter variable for the array indexing
      $i = 0;

      // form a single dimensional array of the dates in unix format for sorting. Note that the key of the array is the actual date.
      foreach ($outstation_data as $data_row) {
        var_dump($data_row['leave_date']);
        $unix_dates_array[$data_row['leave_date']] = strtotime($data_row['leave_date']);
      }


      // predefined PHP function for sort.
      asort($unix_dates_array);

      // counter variable for the array indexing
      $i = 0;

      // array declaration
      $sorted_oustation_data = array();

      // form the final sorted form of the outstation_data
      foreach($unix_dates_array as $key => $date) {
        foreach ($outstation_data as $data_row) {
          if($key == $data_row['leave_date']) {
            $sorted_oustation_data[$i++] = $data_row;
          }
        }
      }

      var_dump($outstation_data);
      var_dump($sorted_oustation_data);

      // write code to check if the dates given by student fall in one of them, and then write queries accordingly.

    } else {

      // the else would mean this student has no prior outstations, just insert it into the table.

    }

  } else {

    // the else here would mean that the student tried to input a date that's less and or equal to today. This else if for error message management.

  }
  
}


?>

<html>
	<head>
    <link href='styles/bootstrap.min.css' rel='stylesheet' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>SWD (Student Welfare Divison)</title>
    <meta charset="UTF-8">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href='https://fonts.googleapis.com/css?family=Cardo' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container">
	<div class="jumbotron">
		<div class="container">
			<center><h1>Online Outstation</h1></center>
		</div>
	</div>
	<div class="col-md-8 col-md-offset-2" style="padding-top:70px">
		<form method="post" class="form-horizontal">
		<fieldset>

		<div class="" style="">
        <?php
        if(isset($_SESSION['success'])) { 
          if($_SESSION['success'] == 0) { ?>
            <div class="alert alert-danger">
               &nbsp;&nbsp; Damn! Something went wrong. Please try again or contact the mess guy. 
            </div>
          <?php } 
          else if($_SESSION['success'] == 1) { ?>
            <div class="alert alert-success">
               &nbsp;&nbsp;  Grace has been submitted sucessfully. 
            </div>
          <?php }
          else if($_SESSION['success'] == -1) { ?>
            <div class="alert alert-danger">
               &nbsp;&nbsp;  Our condolences are with you. You're all out of graces. 
            </div>
          <?php }
          else if($_SESSION['success'] == -2) { ?>
            <div class="alert alert-danger">
               &nbsp;&nbsp;  Don't act smart. You already have a grace for one of the days you mentioned. Be a man, do the right thing! 
            </div>
          <?php }
            }?>
      	</div>

		<!-- Text input-->
		<!-- Text input-->
    <div class="form-group">
      <label id = "leave_date_in" class="col-md-4 control-label" for="leave_date_in">Date</label>  
      <div class="col-md-4">
        <input id="leave_date_in" name="leave_date_in" placeholder="" class="form-control input-md" type="date" min=required>
      </div>
      <div class="col-md-1"> <button onclick="myFunction()" id="mt1d" name="mt1d" class="btn btn-inverted">More than 1 day</button> </div>
    </div>

    <div id="leave_date_out" class="form-group">
      
    </div>

		<!-- Button -->
		<div class="form-group">
		<label class="col-md-4 control-label" for="submit"></label>
		<div class="col-md-8">
		<button id="submit" name="submit" class="btn btn-success">Submit</button>
		</div>
		</div>
		</fieldset>
		</form>

    <script>
      function myFunction() {
        document.getElementById("leave_date_in").innerHTML = "From";
        document.getElementById("leave_date_out").innerHTML = "<label class=\"col-md-4 control-label\" for=\"leave_date_out\">To</label> <div class=\"col-md-4\"><input id=\"leave_date_out\" name=\"leave_date_out\" placeholder=\"\" class=\"form-control input-md\" type=\"date\" required></div>";
      }
    </script>

	</div>
</div>
</body>
</html>
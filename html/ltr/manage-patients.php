<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php'; ?>
<?php
// Fetch data for edit if 'edit' is clicked
if (isset($_GET['edit'])) {
  $edit_id = $_GET['edit'];
  $edit_result = mysqli_query($conn, "SELECT * FROM patients WHERE patient_id = $edit_id");
  $edit_data = mysqli_fetch_assoc($edit_result);
}

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
     <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    input, select { margin: 5px; padding: 5px; }
    table { margin-top: 20px; border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
    .form-container { background: #f5f5f5; padding: 15px; width: fit-content; border-radius: 10px; }
    #sidebarnav {
    margin-top: 30px;
}

  </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <title>Manage Patients - YouCare Admin</title>
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full" data-boxed-layout="full">
       <!-- header -->
         <?php include 'topbar.php'; ?>

       <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                       
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Starter Page</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <h2>Manage Patients</h2>


      <div class="container-fluid">
        <!-- Form -->
        <div class="card">
          <div class="card-body">
<h4 class="card-title"><?php echo isset($edit_data) ? 'Edit Patient' : 'Add New Patient'; ?></h4>  
            <form method="POST">
              <input type="hidden" name="edit_id" value="<?php echo isset($edit_data) ? $edit_data['patient_id'] : ''; ?>">

              <div class="row">
                <div class="col-md-6 mb-3">
<input type="text" name="full_name" class="form-control" placeholder="Full Name" required
value="<?php echo isset($edit_data) ? $edit_data['full_name'] : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
<input type="text" name="phone" class="form-control" placeholder="Phone" pattern="[0-9]{10}" title="Enter 10-digit number" required
value="<?php echo isset($edit_data) ? $edit_data['phone'] : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
<input type="email" name="email" class="form-control" placeholder="Email" required
value="<?php echo isset($edit_data) ? $edit_data['email'] : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
<input type="text" name="address" class="form-control" placeholder="Address" required
value="<?php echo isset($edit_data) ? $edit_data['address'] : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
<input type="number" name="city_id" class="form-control" placeholder="City ID" required
value="<?php echo isset($edit_data) ? $edit_data['city_id'] : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
<input type="number" name="user_id" class="form-control" placeholder="User ID" required
value="<?php echo isset($edit_data) ? $edit_data['user_id'] : ''; ?>">
                </div>
                <div class="col-12">
<button type="submit" name="<?php echo isset($edit_data) ? 'update' : 'add'; ?>" class="btn btn-<?php echo isset($edit_data) ? 'info' : 'success'; ?>">
  <?php echo isset($edit_data) ? 'Update Patient' : 'Add Patient'; ?>
</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- PHP Logic -->
        <?php
        if (isset($_POST['add'])) {
          $full_name = $_POST['full_name'];
          $phone = $_POST['phone'];
          $email = $_POST['email'];
          $address = $_POST['address'];
          $city_id = $_POST['city_id'];
          $user_id = $_POST['user_id'];

          // Full Name: only letters and spaces
if (!preg_match("/^[A-Za-z\s]{2,50}$/", $full_name)) {
    echo "<script>alert('Full Name should contain only letters and spaces (2–50 characters).'); window.history.back();</script>";
    exit;
}

// Phone: digits only, 10–15 digits
if (!preg_match("/^\d{10,15}$/", $phone)) {
    echo "<script>alert('Phone number must be 10 to 15 digits.'); window.history.back();</script>";
    exit;
    
}

// Email: valid format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.'); window.history.back();</script>";
    exit;
}

// Check for duplicate phone or email
$check = mysqli_query($conn, "SELECT * FROM patients WHERE email='$email' OR phone='$phone'");
if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('Email or Phone already exists.'); window.history.back();</script>";
    exit;
}

// Address: min 5 characters, max 100
if (strlen($address) < 5 || strlen($address) > 100) {
    echo "<script>alert('Address should be 5 to 100 characters.'); window.history.back();</script>";
    exit;
}

// City ID and User ID: must be positive integers
if ($city_id <= 0 || $user_id <= 0) {
    echo "<script>alert('Invalid City ID or User ID.'); window.history.back();</script>";
    exit;
}

          $query = "INSERT INTO patients (user_id, full_name, phone, email, address, city_id)
                    VALUES ('$user_id', '$full_name', '$phone', '$email', '$address', '$city_id')";
          mysqli_query($conn, $query);
          echo "<script>window.location='manage-patients.php';</script>";
        }
        if (isset($_POST['update'])) {
  $id = $_POST['edit_id'];
  $full_name = $_POST['full_name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $city_id = $_POST['city_id'];
  $user_id = $_POST['user_id'];

  // Trim and sanitize inputs
$full_name = trim($_POST['full_name']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$address = trim($_POST['address']);
$city_id = intval($_POST['city_id']);
$user_id = intval($_POST['user_id']);

// Full Name: only letters and spaces
if (!preg_match("/^[A-Za-z\s]{2,50}$/", $full_name)) {
    echo "<script>alert('Full Name should contain only letters and spaces (2–50 characters).'); window.history.back();</script>";
    exit;
}

// Phone: digits only, 10–15 digits
if (!preg_match("/^\d{10,15}$/", $phone)) {
    echo "<script>alert('Phone number must be 10 to 15 digits.'); window.history.back();</script>";
    exit;
}

// Email: valid format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.'); window.history.back();</script>";
    exit;
}

// Check for duplicate phone or email
$check = mysqli_query($conn, "SELECT * FROM patients WHERE (email='$email' OR phone='$phone') AND patient_id != $id");
if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('Email or Phone already exists.'); window.history.back();</script>";
    exit;
}

// Address: min 5 characters, max 100
if (strlen($address) < 5 || strlen($address) > 100) {
    echo "<script>alert('Address should be 5 to 100 characters.'); window.history.back();</script>";
    exit;
}

// City ID and User ID: must be positive integers
if ($city_id <= 0 || $user_id <= 0) {
    echo "<script>alert('Invalid City ID or User ID.'); window.history.back();</script>";
    exit;
}


  $query = "UPDATE patients SET 
    user_id='$user_id', full_name='$full_name', phone='$phone', 
    email='$email', address='$address', city_id='$city_id' 
    WHERE patient_id=$id";

  mysqli_query($conn, $query);
  echo "<script>window.location='manage-patients.php';</script>";
}


        if (isset($_GET['delete'])) {
          $id = $_GET['delete'];
          mysqli_query($conn, "DELETE FROM patients WHERE patient_id = $id");
          echo "<script>window.location='manage-patients.php';</script>";
        }


        ?>

        <!-- Table -->
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Patients List</h4>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>City ID</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $result = mysqli_query($conn, "SELECT * FROM patients");
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['patient_id']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['city_id']}</td>
                            <td>
  <a href='manage-patients.php?edit={$row['patient_id']}' class='btn btn-sm btn-primary' title='Edit'>
    <i class='mdi mdi-pencil'></i>
  </a>
  <a href='manage-patients.php?delete={$row['patient_id']}' class='btn btn-sm btn-danger' title='Delete' onclick='return confirm(\"Delete this patient?\")'>
    <i class='mdi mdi-delete'></i>
  </a>
</td>

                          </tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
               All Rights Reserved by YouCare Admin.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
     
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../../dist/js/custom.min.js"></script>
</body>

</html>
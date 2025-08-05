<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php'; ?>
<?php
 if (isset($_GET['edit'])) {
  $edit_id = $_GET['edit'];
  $edit_result = mysqli_query($conn, "SELECT * FROM doctors WHERE doctor_id = $edit_id");
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
.action-column {
    width: 100px; /* control how wide this column can be */
    text-align: center;
    white-space: nowrap;
}
.breadcrumb-item{
  
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
    <title>Nice admin Template - The Ultimate Multipurpose admin template</title>
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
      <!-- header  -->
        <!-- header -->
         <?php include 'topbar.php'; ?>
       <!-- Sidebar -->
         <?php include 'sidebar.php'; ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                      
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#">Home</a>
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
                <h2>Manage Doctors</h2>

  <div class="card">
  <div class="card-body">
<h4 class="card-title"><?php echo isset($edit_data) ? 'Edit Doctor' : 'Add New Doctor'; ?></h4>
<form method="POST">
  <div class="row">

    <input type="hidden" name="edit_id" value="<?php echo isset($edit_data) ? $edit_data['doctor_id'] : ''; ?>">

    <div class="col-md-6 mb-3">
      <input type="text" name="full_name" class="form-control" placeholder="Full Name" required 
      value="<?php echo isset($edit_data) ? $edit_data['full_name'] : ''; ?>">
    </div>

    <div class="col-md-6 mb-3">
      <input type="text" name="specialization" class="form-control" placeholder="Specialization" required 
      value="<?php echo isset($edit_data) ? $edit_data['specialization'] : ''; ?>">
    </div>

    <div class="col-md-6 mb-3">
      <input type="text" name="phone" class="form-control" placeholder="Phone" required 
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
      <textarea name="profile_details" class="form-control" placeholder="Profile Details" rows="3"><?php 
        echo isset($edit_data) ? $edit_data['profile_details'] : ''; ?></textarea>
    </div>
        <div class="col-12">
<button type="submit" name="<?php echo isset($edit_data) ? 'update' : 'add'; ?>" class="btn btn-<?php echo isset($edit_data) ? 'info' : 'success'; ?>">
  <?php echo isset($edit_data) ? 'Update Doctor' : '+ Add Doctor'; ?>
</button>
        </div>
      </div>
    </form>
  </div>
</div>


  <?php
  // INSERT Logic

  if (isset($_POST['add'])) {
    $full_name = $_POST['full_name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city_id = $_POST['city_id'];
    $profile_details = $_POST['profile_details'];

    $query = "INSERT INTO doctors (full_name, specialization, phone, email, address, city_id, profile_details)
              VALUES ('$full_name', '$specialization', '$phone', '$email', '$address', '$city_id', '$profile_details')";

    mysqli_query($conn, $query);
    echo "<script>window.location='manage-doctors.php';</script>";
  }

if (isset($_POST['update'])) {
  $id = $_POST['edit_id'];
  $full_name = $_POST['full_name'];
  $specialization = $_POST['specialization'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $city_id = $_POST['city_id'];
  $profile_details = $_POST['profile_details'];

  $query = "UPDATE doctors SET 
    full_name='$full_name', specialization='$specialization', phone='$phone', email='$email',
    address='$address', city_id='$city_id', profile_details='$profile_details' 
    WHERE doctor_id=$id";
    
  mysqli_query($conn, $query);
  echo "<script>window.location='manage-doctors.php';</script>";
}

  // DELETE Logic
  if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM doctors WHERE doctor_id = $id");
    echo "<script>window.location='manage-doctors.php';</script>";
  }
  ?>

  <table>
    <thead>
      <tr>
        <th>Doctor ID</th>
        <th>Full Name</th>
        <th>Specialization</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Address</th>
        <th>City ID</th>
        <th>Profile</th>
        <th class="action-column">Action</th>
      </tr>
    </thead>
   <tbody>
<?php
$result = mysqli_query($conn, "SELECT * FROM doctors");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['doctor_id']}</td>
            <td>{$row['full_name']}</td>
            <td>{$row['specialization']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['email']}</td>
            <td>{$row['address']}</td>
            <td>{$row['city_id']}</td>
            <td>{$row['profile_details']}</td>
            <td class='action-column'>
  <a href='manage-doctors.php?edit={$row['doctor_id']}' class='btn btn-sm btn-primary' title='Edit'>
    <i class='mdi mdi-pencil'></i>
  </a>
  <a href='manage-doctors.php?delete={$row['doctor_id']}' class='btn btn-sm btn-danger' title='Delete' onclick=\"return confirm('Delete this doctor?')\">
    <i class='mdi mdi-delete'></i>
  </a>
</td>

          </tr>";
}
?>
</tbody>

  </table>
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
                All Rights Reserved by Nice admin. Designed and Developed by
                <a href="https://wrappixel.com">WrapPixel</a>.
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
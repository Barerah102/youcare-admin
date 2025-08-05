<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Doctors</title>
  <link href="assets/css/style.css" rel="stylesheet"> <!-- Adjust path if needed -->
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    input, select { margin: 5px; padding: 5px; }
    table { margin-top: 20px; border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
    .form-container { background: #f5f5f5; padding: 15px; width: fit-content; border-radius: 10px; }
  </style>
</head>
<body>

  <h2>Manage Doctors</h2>

  <div class="form-container">
    <form method="POST">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="text" name="specialization" placeholder="Specialization" required>
      <input type="text" name="phone" placeholder="Phone" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="address" placeholder="Address" required>
      <input type="number" name="city_id" placeholder="City ID" required>
      <textarea name="profile_details" placeholder="Profile Details"></textarea>
      <button type="submit" name="add">Add Doctor</button>
    </form>
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
        <th>Action</th>
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
                <td><a href='manage-doctors.php?delete={$row['doctor_id']}' onclick='return confirm(\"Delete this doctor?\")'>Delete</a></td>
              </tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>


<div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full" data-boxed-layout="full">

    <!-- Topbar -->
    <?php include 'topbar.php'; ?>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Patient Messages</h4>
                </div>
                <div class="col-7 align-self-center">
                    <div class="d-flex align-items-center justify-content-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Messages</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container Fluid -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Messages from Patients</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Received On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($conn, "SELECT * FROM messages ORDER BY message_id DESC");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>{$row['message_id']}</td>
                                            <td>{$row['full_name']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['subject']}</td>
                                            <td>{$row['message']}</td>
                                            <td>{$row['created_at']}</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Messages - YouCare Admin</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <link href="../../dist/css/style.min.css" rel="stylesheet">
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

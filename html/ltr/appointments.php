<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php'; 
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
        #sidebarnav { margin-top: 30px; }
        .action-icons i { cursor: pointer; margin: 0 5px; }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Appointments - YouCare Admin</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <link href="../../dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full" data-boxed-layout="full">
        <?php include 'topbar.php'; ?>
        <?php include 'sidebar.php'; ?>

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Manage Appointments</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Appointments</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Appointments List</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient Name</th>
                                        <th>Doctor Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$query = "
    SELECT a.*, 
           p.full_name AS patient_name, 
           d.full_name AS doctor_name
    FROM appointments a
    LEFT JOIN patients p ON a.patient_id = p.patient_id
LEFT JOIN doctors d ON a.doctor_id = d.doctor_id

";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td>{$row['appointment_id']}</td>
                                            <td>{$row['patient_name']}</td>
                                            <td>{$row['doctor_name']}</td>
                                            <td>{$row['appointment_datetime']}</td>
                                            <td>{$row['booked_on']}</td>
                                            <td id='status-cell-{$row['appointment_id']}'>";
                                        
if ($row['status'] == 'Pending' || empty($row['status'])) {
                                            echo "<form method='post' action='appointments.php' class='status-form'>
                                                    <input type='hidden' name='appointment_id' value='{$row['appointment_id']}'>
                                                    <select name='status' class='status-dropdown' onchange='this.form.submit()'>
                                                        <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                        <option value='Cancelled'>Cancelled</option>
                                                        <option value='Completed'>Completed</option>
                                                    </select>
                                                  </form>";
                                        } else {
                                            echo "<span>{$row['status']}</span>";
                                        }

                                        echo "</td>
                                            <td class='action-icons'>
                                                <i class='fas fa-edit text-primary' onclick='enableEditing({$row['appointment_id']})'></i>
                                                <a href='appointments.php?delete={$row['appointment_id']}' onclick='return confirm(\"Delete this appointment?\")'><i class='fas fa-trash text-danger'></i></a>
                                            </td>
                                        </tr>";
                                    }

                                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
                                        $id = $_POST['appointment_id'];
                                        $status = mysqli_real_escape_string($conn, $_POST['status']);
                                        mysqli_query($conn, "UPDATE appointments SET status='$status' WHERE appointment_id=$id");
                                        echo "<script>window.location='appointments.php';</script>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php
                if (isset($_GET['delete'])) {
                    $id = $_GET['delete'];
                    mysqli_query($conn, "DELETE FROM appointments WHERE appointment_id = $id");
                    echo "<script>window.location='appointments.php';</script>";
                }
                ?>
            </div>

            <footer class="footer text-center">
                All Rights Reserved by YouCare Admin.
            </footer>
        </div>
    </div>

    <script>
        function enableEditing(id) {
            const cell = document.getElementById(`status-cell-${id}`);
            cell.innerHTML = `
                <form method='post' action='appointments.php'>
                    <input type='hidden' name='appointment_id' value='${id}'>
                    <select name='status' class='status-dropdown' onchange='this.form.submit()'>
                        <option value='Pending'>Pending</option>
                        <option value='Cancelled'>Cancelled</option>
                        <option value='Completed'>Completed</option>
                    </select>
                </form>
            `;
        }
    </script>

    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="../../dist/js/waves.js"></script>
    <script src="../../dist/js/sidebarmenu.js"></script>
    <script src="../../dist/js/custom.min.js"></script>
</body>
</html>

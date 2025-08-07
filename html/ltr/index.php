<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';


// Fetch total counts
$total_patients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM patients"))['count'];
$total_doctors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM doctors"))['count'];
$upcoming_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE appointment_datetime >= CURDATE()"))['count'];
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="YouCare Admin Panel">
    <meta name="author" content="Queen">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <title>YouCare Dashboard</title>
    <link href="../../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../../dist/css/style.min.css" rel="stylesheet">

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
 <?php include "topbar.php" ?>

 <?php include "sidebar.php" ?>
        
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Container fluid  -->
            <!-- ============================================================== -->
           <div class="container-fluid">
    <!-- Appointment Overview Chart -->
    <div class="row">
    <!-- Left Column with Image -->
<div class="col-md-4 d-flex flex-column justify-content-between" style="height: 100%;">
        <div class="card">
            <div class="card-body text-center">
                <img src="../../admin.jpg" alt="Appointments Overview" class="img-fluid mt-3" >
            </div>
        </div>
    </div>

    <!-- Right Column - Stats Summary -->
    <div class="col-md-4">
        <!-- Total Appointments Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title m-b-5">Total Appointments</h5>
                <h3 class="font-light">
                    <?php echo $upcoming_appointments; ?>
                </h3>
                <div class="m-t-20 text-center">
                    <div id="appointmentsSummaryChart"></div>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
      <div class="card">
    <div class="card-body">
        <h4 class="card-title m-b-0">Users</h4>
        <h2 class="font-light">
            <?php echo $total_patients + $total_doctors; ?>
        </h2>
        <div class="m-t-30">
            <div class="row text-center">
                <div class="col-6 border-right">
                    <h4 class="m-b-0"><?php echo $total_patients; ?></h4>
                    <span class="font-14 text-muted">Patients</span>
                </div>
                <div class="col-6">
                    <h4 class="m-b-0"><?php echo $total_doctors; ?></h4>
                    <span class="font-14 text-muted">Doctors</span>
                </div>
            </div>
        </div>
    </div>
</div>

    </div> <!-- End Right Column -->
</div>

<!-- Chartist JS -->
<script>
    new Chartist.Bar('#appointmentsChart', {
        labels: ['Patients', 'Doctors', 'Appointments'],
        series: [
            [<?php echo $total_patients; ?>, <?php echo $total_doctors; ?>, <?php echo $upcoming_appointments; ?>]
        ]
    }, {
        distributeSeries: true
    });
</script>

                <!-- ============================================================== -->
                <!-- Email campaign chart -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Ravenue - page-view-bounce rate -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- column -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <h4 class="card-title">Recent Appointments</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">Patient</th>
                                            <th class="border-top-0">Doctor</th>
                                            <th class="border-top-0">Date</th>
                                            <th class="border-top-0">Status</th>

                                        </tr>
                                    </thead>
                                    <?php
$query = "SELECT 
             a.appointment_datetime, 
             a.status, 
             p.full_name AS patient, 
             d.full_name AS doctor
          FROM appointments a
          JOIN patients p ON a.patient_id = p.patient_id
          JOIN doctors d ON a.doctor_id = d.doctor_id
          ORDER BY a.appointment_datetime DESC
          LIMIT 6";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $status = htmlspecialchars($row['status']);
        $label = 'label-info';

        if ($status === 'Confirmed') $label = 'label-success';
        elseif ($status === 'Cancelled') $label = 'label-danger';
        elseif ($status === 'Pending') $label = 'label-warning';

        echo "<tr>";
        echo "<td class='txt-oflo'>" . htmlspecialchars($row['patient']) . "</td>";
        echo "<td class='txt-oflo'>" . htmlspecialchars($row['doctor']) . "</td>";
        echo "<td class='txt-oflo'>" . date('F j, Y', strtotime($row['appointment_datetime'])) . "</td>";
        echo "<td><span class='label label-rounded $label'>$status</span></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' class='text-center'>No appointments found.</td></tr>";
}
?>
     </tbody>
        </table>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Ravenue - page-view-bounce rate -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
                <!-- ============================================================== -->
     <div class="row">
    <!-- Left Column: Recent Messages -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">📬 Recent Messages</h4>
            </div>
            <div class="comment-widgets" style="height:430px;">
                <?php
                $query = "SELECT name, subject, message, created_at FROM messages ORDER BY created_at DESC LIMIT 4";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $name = htmlspecialchars($row['name']);
                        $initial = strtoupper(substr($name, 0, 1));
                        $subject = htmlspecialchars($row['subject']);
                        $preview = htmlspecialchars(substr($row['message'], 0, 80)) . '...';
                        $date = date('F j, Y', strtotime($row['created_at']));
                        echo '
                        <div class="d-flex flex-row comment-row m-t-0">
                            <div class="p-2">
    <div class="rounded-circle bg-info text-white text-center" 
         style="width: 50px; height: 50px; line-height: 50px; font-size: 20px;">
    ' . $initial . '
    </div>
</div>



                            <div class="comment-text w-100">
                                <h6 class="font-medium">' . $name . '</h6>
                                <strong>' . $subject . '</strong>
                                <span class="m-b-15 d-block">' . $preview . '</span>
                                <div class="comment-footer">
                                    <span class="text-muted float-right">' . $date . '</span>
                                    <span class="label label-rounded label-info">New</span>
                                    <span class="action-icons">
                                        <a href="messages.php">
                                            <i class="ti-arrow-right"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="text-center p-3 text-muted">No messages yet.</div>';
                }
                ?>
            </div>
            <div class="text-center" style="margin-bottom: 25px;">
    <a href="messages.php" class="btn btn-sm btn-outline-info">View All Messages</a>
</div>

        </div>
    </div>

    <!-- Right Column: Monthly Overview -->
    <div class="col-lg-6">
    <div class="card">
        <div class="card-body p-0 d-flex flex-column" style="height: 565px;">
            
            <!-- Image covering upper part -->
<div style="height: 230px; overflow: hidden;">
    <img src="../../month.jpg" alt="Overview Image" style="width: 100%; height: 100%; object-fit: cover;">
</div>

<!-- Remaining content -->
<div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
    <h4 class="card-title mb-3 text-center" style="margin-top: 10px;">📊 Monthly Overview</h4>
                
                <?php
                // Appointments this month
                $appointments = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE MONTH(appointment_datetime) = MONTH(CURDATE()) AND YEAR(appointment_datetime) = YEAR(CURDATE())");
                $appointments_count = mysqli_fetch_assoc($appointments)['total'];

                // New Patients this month
                $patients = mysqli_query($conn, "SELECT COUNT(*) AS total FROM patients WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
                $patients_count = mysqli_fetch_assoc($patients)['total'];

                // Messages this month
                $messages = mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
                $messages_count = mysqli_fetch_assoc($messages)['total'];
                ?>

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item">🩺 Appointments Booked: <b><?= $appointments_count ?></b></li>
                    <li class="list-group-item">👥 New Users: <b><?= $patients_count ?></b></li>
                    <li class="list-group-item">📩 Messages Received: <b><?= $messages_count ?></b></li>
                </ul>

                <div class="text-center">
                    <a href="appointments.php" class="btn btn-sm btn-outline-info">View Full Report</a>
                </div>
            </div>
        </div>
    </div>
</div>



                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
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
    <!--This page JavaScript -->
    <!--chartis chart-->
    <script src="../../assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="../../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../../dist/js/pages/dashboards/dashboard1.js"></script>
    
    
</body>

</html>
<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>YouCare Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: #f3f3f3;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-wrapper {
            width: 700px;
            background: white;
            display: flex;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .login-banner {
            background: #0c4f21;
            color: white;
            width: 40%;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .login-banner h1 {
            font-size: 32px;
            margin: 0;
        }

        .login-form {
            width: 60%;
            padding: 50px 40px;
        }

        .login-form h2 {
            margin-bottom: 30px;
            color: #0c4f21;
        }

        .login-form input {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .login-form button {
            width: 100%;
            background: #0c4f21;
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
        }

        .login-form button:hover {
            background: #093d19;
        }

        .error {
            color: red;
            margin-top: 15px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                width: 90%;
            }

            .login-banner, .login-form {
                width: 100%;
                text-align: center;
            }

            .login-banner {
                padding: 30px;
            }

            .login-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-banner">
  <img src="../../logo.png" alt="YouCare Logo" style="width: 80px; margin-bottom: 10px;">
  <h1 style="color: #ffffffff;">YouCare</h1>
  <p>Admin Panel</p>
</div>

        <div class="login-form">
            <h2>Admin Login</h2>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="login">Login</button>
            </form>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        </div>
    </div>
</body>
</html>

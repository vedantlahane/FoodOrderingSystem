<?php
include('../config/constants.php');
?>

<html>
    <head>
        <title>Login - Food Order System</title>
        <link rel="stylesheet" href="../css/admin.css">
    </head>

    <body>
        <div class="login">
            <h1 class="text-center">Login</h1>
            <br><br>

            <?php
            if (isset($_SESSION['login'])) {
                echo $_SESSION['login'];
                unset($_SESSION['login']);
            }

            if (isset($_SESSION['no-login-message'])) {
                echo $_SESSION['no-login-message'];
                unset($_SESSION['no-login-message']);
            }
            ?>
            <br><br>

            <form action="" method="POST" class="text-center">
                Username: <br>
                <input type="text" name="username" placeholder="Enter Username"><br><br>
                Password: <br>
                <input type="password" name="password" placeholder="Enter Password"><br><br>
                <input type="submit" name="submit" value="Login" class="btn-primary">
                <br><br>
            </form>

            <p class="text-center">Created By - Vedant Lahane</p>
        </div>
    </body>
</html>

<?php
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $raw_password = $_POST['password'];

    $sql = "SELECT * FROM admin_table WHERE username='$username'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $stored_password = $row['password'];

        if (password_verify($raw_password, $stored_password)) {
            $_SESSION['login'] = "<div class='success'>Login Successful.</div>";
            $_SESSION['user'] = $username;
            header('location:' . SITEURL . 'admin/');
        } else {
            $_SESSION['login'] = "<div class='error text-center'>Username or Password did not match.</div>";
            header('location:' . SITEURL . 'admin/login.php');
        }
    } else {
        $_SESSION['login'] = "<div class='error text-center'>Username or Password did not match.</div>";
        header('location:' . SITEURL . 'admin/login.php');
    }
}
?>

<?php
include('../config/constants.php');
?>

<html>
    <head>
        <title>Login - Food Order System</title>
    </head>

    <body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">

        <div class="login" style="max-width: 400px; margin: 100px auto; padding: 20px; background: #ffffff; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
            <h1 class="text-center" style="text-align: center; color: #333; margin-bottom: 20px; font-size: 24px;">Login</h1>
            <br>

            <?php
            if (isset($_SESSION['login'])) {
                echo '<div style="text-align: center; color: green; margin-bottom: 10px;">' . $_SESSION['login'] . '</div>';
                unset($_SESSION['login']);
            }

            if (isset($_SESSION['no-login-message'])) {
                echo '<div style="text-align: center; color: red; margin-bottom: 10px;">' . $_SESSION['no-login-message'] . '</div>';
                unset($_SESSION['no-login-message']);
            }
            ?>
            <br>

            <form action="" method="POST" class="text-center" style="text-align: center;">
                <label for="username" style="display: block; font-weight: bold; margin-bottom: 5px;">Username:</label>
                <input type="text" name="username" placeholder="Enter Username" style="width: 90%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px;">

                <label for="password" style="display: block; font-weight: bold; margin-bottom: 5px;">Password:</label>
                <input type="password" name="password" placeholder="Enter Password" style="width: 90%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px;">

                <input type="submit" name="submit" value="Login" class="btn-primary" style="width: 90%; padding: 10px; background-color: #0078D4; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 10px;">
                <br><br>
            </form>

            <p class="text-center" style="text-align: center; font-size: 14px; color: #888; margin-top: 10px;">Created By - Vedant Lahane</p>
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
            $_SESSION['login'] = "<div class='success' style='color: green; text-align: center;'>Login Successful.</div>";
            $_SESSION['user'] = $username;
            header('location:' . SITEURL . 'admin/');
        } else {
            $_SESSION['login'] = "<div class='error text-center' style='color: red; text-align: center;'>Username or Password did not match.</div>";
            header('location:' . SITEURL . 'admin/login.php');
        }
    } else {
        $_SESSION['login'] = "<div class='error text-center' style='color: red; text-align: center;'>Username or Password did not match.</div>";
        header('location:' . SITEURL . 'admin/login.php');
    }
}
?>
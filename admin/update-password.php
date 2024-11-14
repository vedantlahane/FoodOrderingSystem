<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Change Password</h1>
        <br><br>

        <?php 
            if(isset($_GET['id'])) {
                $id=$_GET['id'];
            }
        ?>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Current Password: </td>
                    <td>
                        <input type="password" name="current_password" placeholder="Current Password">
                    </td>
                </tr>

                <tr>
                    <td>New Password:</td>
                    <td>
                        <input type="password" name="new_password" placeholder="New Password">
                    </td>
                </tr>

                <tr>
                    <td>Confirm Password: </td>
                    <td>
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Change Password" class="btn-secondary">
                    </td>
                </tr>

            </table>
        </form>
    </div>
</div>

<?php 

    // Check whether the Submit Button is Clicked or Not
    if(isset($_POST['submit'])) {

        // 1. Get the Data from Form
        $id=$_POST['id'];
        $current_password = $_POST['current_password'];  // No hashing yet
        $new_password = $_POST['new_password'];  // No hashing yet
        $confirm_password = $_POST['confirm_password'];  // No hashing yet

        // 2. Check whether the user with current ID and Current Password Exists or Not
        $sql = "SELECT * FROM admin_table WHERE id=$id";

        // Execute the Query
        $res = mysqli_query($conn, $sql);

        if($res == true) {
            // Check whether data is available or not
            $count = mysqli_num_rows($res);

            if($count == 1) {
                // User Exists, Now check the password
                $row = mysqli_fetch_assoc($res);
                $stored_password = $row['password'];  // Stored hashed password

                // Verify the current password using password_verify()
                if(password_verify($current_password, $stored_password)) {
                    // User is authenticated, now check the new password and confirm password
                    if($new_password == $confirm_password) {
                        // Hash the new password before saving it
                        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                        // Update the Password
                        $sql2 = "UPDATE admin_table SET 
                                    password='$new_password_hashed' 
                                    WHERE id=$id";

                        // Execute the Query
                        $res2 = mysqli_query($conn, $sql2);

                        // Check whether the query executed or not
                        if($res2 == true) {
                            // Display Success Message
                            $_SESSION['change-pwd'] = "<div class='success'>Password Changed Successfully. </div>";
                            // Redirect the User
                            header('location:'.SITEURL.'admin/manage-admin.php');
                        } else {
                            // Display Error Message
                            $_SESSION['change-pwd'] = "<div class='error'>Failed to Change Password. </div>";
                            // Redirect the User
                            header('location:'.SITEURL.'admin/manage-admin.php');
                        }
                    } else {
                        // New and Confirm passwords don't match
                        $_SESSION['pwd-not-match'] = "<div class='error'>Password Did not Match. </div>";
                        header('location:'.SITEURL.'admin/manage-admin.php');
                    }
                } else {
                    // Current password does not match
                    $_SESSION['user-not-found'] = "<div class='error'>Incorrect Current Password. </div>";
                    header('location:'.SITEURL.'admin/manage-admin.php');
                }
            } else {
                // User Does not Exist
                $_SESSION['user-not-found'] = "<div class='error'>User Not Found. </div>";
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
        }
    }
?>

<?php include('partials/footer.php'); ?>

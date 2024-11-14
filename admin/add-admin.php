<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>

        <br><br>

        <?php 
            if (isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
        ?>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="full_name" placeholder="Enter Your Name" required>
                    </td>
                </tr>
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username" required>
                    </td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>

<?php
if (isset($_POST['submit'])) {
    // 1. Sanitize the input
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Password hashing

    // 2. SQL query to insert data into the database
    $sql = "INSERT INTO admin_table SET 
            full_name='$full_name',
            username='$username',
            password='$password'";

    // 3. Execute the query and save data in the database
    $res = mysqli_query($conn, $sql);

    // 4. Check whether the query executed successfully
    if ($res) {
        $_SESSION['add'] = "<div class='success'>Admin Added Successfully.</div>";
        header("location:" . SITEURL . 'admin/manage-admin.php');
    } else {
        $_SESSION['add'] = "<div class='error'>Failed to Add Admin.</div>";
        header("location:" . SITEURL . 'admin/add-admin.php');
    }
}
?>

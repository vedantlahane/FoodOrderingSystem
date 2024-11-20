

<?php include('partials/menu.php'); 
include('partials/login-check.php')?>

<!-- Main Content Section Starts -->
<div class="main-content">
    <div class="wrapper">
        <h1>Dashboard</h1>
        <br><br>
        <?php 
            // Check if session for login exists and display it
            if (isset($_SESSION['login'])) {
                echo $_SESSION['login'];
                unset($_SESSION['login']);
            }
        ?>
        <br><br>

        <div class="col-4 text-center">
            <?php 
                // SQL Query to get category count
                $sql = "SELECT * FROM category_table";
                $res = mysqli_query($conn, $sql);
                if ($res) {
                    $count = mysqli_num_rows($res);
                    echo "<h1>$count</h1>";
                } else {
                    echo "<h1>0</h1>";
                }
            ?>
            <br />
            Categories
        </div>

        <div class="col-4 text-center">
            <?php 
                // SQL Query to get food count
                $sql2 = "SELECT * FROM food_table";
                $res2 = mysqli_query($conn, $sql2);
                if ($res2) {
                    $count2 = mysqli_num_rows($res2);
                    echo "<h1>$count2</h1>";
                } else {
                    echo "<h1>0</h1>";
                }
            ?>
            <br />
            Foods
        </div>

        <div class="col-4 text-center">
            <?php 
                // SQL Query to get order count
                $sql3 = "SELECT * FROM order_table";
                $res3 = mysqli_query($conn, $sql3);
                if ($res3) {
                    $count3 = mysqli_num_rows($res3);
                    echo "<h1>$count3</h1>";
                } else {
                    echo "<h1>0</h1>";
                }
            ?>
            <br />
            Total Orders
        </div>

        <div class="col-4 text-center">
            <?php 
                // SQL Query to get total revenue for delivered orders
                $sql4 = "SELECT SUM(total) AS Total FROM order_table WHERE status='Delivered'";
                $res4 = mysqli_query($conn, $sql4);
                if ($res4) {
                    $row4 = mysqli_fetch_assoc($res4);
                    $total_revenue = $row4['Total'] ? $row4['Total'] : 0;
                    echo "<h1>Rs " . number_format($total_revenue, 2) . "</h1>";
                } else {
                    echo "<h1>Rs 0.00</h1>";
                }
            ?>
            <br />
            Revenue Generated
        </div>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Main Content Section Ends -->

<?php include('partials/footer.php'); ?>

user_id = '".$_SESSION['user_id']."'

<?php include('partials-front/menu.php'); ?>

<?php 
    // Check whether user is logged in or not
    if(!isset($_SESSION['user_id'])) {
        // User is not logged in, redirect to login page
        header('location:'.SITEURL.'login.php');
    }

    // Check whether food id is set or not
    if(isset($_GET['food_id'])) {
        // Get the Food id and details of the selected food
        $food_id = $_GET['food_id'];

        // Get the Details of the Selected Food
        $sql = "SELECT * FROM food_table WHERE id=$food_id";
        // Execute the Query
        $res = mysqli_query($conn, $sql);
        // Count the rows
        $count = mysqli_num_rows($res);
        // Check whether the data is available or not
        if($count==1) {
            // We Have Data
            // Get the Data from Database
            $row = mysqli_fetch_assoc($res);

            $title = $row['title'];
            $price = $row['price'];
            $image_name = $row['image_name'];
        } else {
            // Food not Available
            // Redirect to Home Page
            header('location:'.SITEURL);
        }
    } else {
        // Redirect to homepage
        header('location:'.SITEURL);
    }
?>

<!-- FOOD SEARCH Section Starts Here -->
<section class="food-search">
    <div class="container">
        
        <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

        <form action="" method="POST" class="order">
            <fieldset>
                <legend>Selected Food</legend>

                <div class="food-menu-img">
                    <?php 
                        // Check whether the image is available or not
                        if($image_name=="") {
                            // Image not Available
                            echo "<div class='error'>Image not Available.</div>";
                        } else {
                            // Image is Available
                            ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                            <?php
                        }
                    ?>
                </div>

                <div class="food-menu-desc">
                    <h3><?php echo $title; ?></h3>
                    <input type="hidden" name="food" value="<?php echo $title; ?>">

                    <p class="food-price">$<?php echo $price; ?></p>
                    <input type="hidden" name="price" value="<?php echo $price; ?>">

                    <div class="order-label">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" value="1" required>
                    
                </div>

            </fieldset>
            
            <fieldset>
                <legend>Delivery Details</legend>
                <div class="order-label">Order Type</div>
                <select name="order_type" class="input-responsive" required>
                    <option value="dine-in">Dine In</option>
                    <option value="take-away">Take Away</option>
                    <option value="delivery">Delivery</option>
                </select>

                <div class="order-label">Full Name</div>
                <input type="text" name="full-name" placeholder="abc abc" class="input-responsive" required>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" placeholder="+91744XXXXXXX" class="input-responsive" required>

                <div class="order-label">Email</div>
                <input type="email" name="email" placeholder="abc@xyz.com" class="input-responsive" required>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" placeholder="home, street, city" class="input-responsive" required></textarea>

                <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
            </fieldset>

        </form>

        <?php 
            // Check whether submit button is clicked or not
            if(isset($_POST['submit'])) {
                // Get all the details from the form
                $food = $_POST['food'];
                $price = $_POST['price'];
                $qty = $_POST['qty'];
                $order_type = $_POST['order_type'];

                $total = $price * $qty; // total = price x qty 

                $order_date = date("Y-m-d h:i:sa"); // Order Date

                $status = "Ordered";  // Ordered, On Delivery, Delivered, Cancelled

                $customer_name = $_POST['full-name'];
                $customer_contact = $_POST['contact'];
                $customer_email = $_POST['email'];
                $customer_address = $_POST['address'];

                // Save the Order in Database
                // Create SQL to save the data
                $sql2 = "INSERT INTO order_table SET 
                    food = '$food',
                    price = $price,
                    quantity = $qty,
                    total = $total,
                    order_date = '$order_date',
                    status = '$status',
                    order_type = '$order_type',
                    customer_name = '$customer_name',
                    customer_contact = '$customer_contact',
                    customer_email = '$customer_email',
                    customer_address = '$customer_address',
                ";

                // Execute the Query
                $res2 = mysqli_query($conn, $sql2);

                // Check whether query executed successfully or not
                if($res2==true) {
                    // Query Executed and Order Saved
                    $_SESSION['order'] = "<div class='success text-center'>Food Ordered Successfully.</div>";
                    header('location:'.SITEURL);
                } else {
                    // Failed to Save Order
                    $_SESSION['order'] = "<div class='error text-center'>Failed to Order Food.</div>";
                    header('location:'.SITEURL);
                }
            }
        ?>

    </div>
</section>
<!-- FOOD SEARCH Section Ends Here -->

<?php include('partials-front/footer.php'); ?>

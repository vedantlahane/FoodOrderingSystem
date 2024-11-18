<?php
// Include the database configuration file
include('./config/constants.php');

// Sanitize inputs
$full_name = mysqli_real_escape_string($conn, 'Admin');
$username = mysqli_real_escape_string($conn, 'admin1');
$password = password_hash('Admin123', PASSWORD_DEFAULT); // Use password_hash to hash the password

// SQL query to insert the new admin
$sql = "INSERT INTO admin_table (full_name, username, password) VALUES ('$full_name', '$username', '$password')";

// Execute the query
$res = mysqli_query($conn, $sql);

// Check if the query was successful
if ($res) {
    echo "Admin added successfully!";
} else {
    echo "Failed to add admin: " . mysqli_error($conn);
}
?>

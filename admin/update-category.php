<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>

        <br><br>

        <?php 
        //Check whether the id is set or not
        if(isset($_GET['id']))
        {
            //Get the ID and all other details
            $id = $_GET['id'];
            //Create SQL Query to get all other details
            $sql = "SELECT * FROM category_table WHERE id=$id";

            //Execute the Query
            $res = mysqli_query($conn, $sql);

            //Count the Rows to check whether the id is valid or not
            $count = mysqli_num_rows($res);

            if($count==1)
            {
                //Get all the data
                $row = mysqli_fetch_assoc($res);
                $title = $row['title'];
                $current_image = $row['image_name'];
                $featured = $row['featured'];
                $active = $row['active'];
            }
            else
            {
                //redirect to manage category with session message
                $_SESSION['no-category-found'] = "<div class='error'>Category not Found.</div>";
                header('location:'.SITEURL.'admin/manage-category.php');
                exit();
            }
        }
        else
        {
            //redirect to Manage Category
            header('location:'.SITEURL.'admin/manage-category.php');
            exit();
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php 
                        if($current_image != "")
                        {
                            ?>
                            <img src="<?php echo SITEURL; ?>images/category/<?php echo htmlspecialchars($current_image); ?>" width="150px">
                            <?php
                        }
                        else
                        {
                            echo "<div class='error'>Image Not Added.</div>";
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image" accept="image/*">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes 
                        <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No"> No 
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes"> Yes 
                        <input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No"> No 
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
        if(isset($_POST['submit']))
        {
            //1. Get all the values from our form
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $current_image = mysqli_real_escape_string($conn, $_POST['current_image']);
            $featured = mysqli_real_escape_string($conn, $_POST['featured']);
            $active = mysqli_real_escape_string($conn, $_POST['active']);

            //2. Updating New Image if selected
            $image_name = $current_image;

            //Check if a new image is uploaded
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0)
            {
                $image_name = $_FILES['image']['name'];

                if($image_name != "")
                {
                    //Auto Rename our Image
                    $temp = explode('.', $image_name);
                    $ext = strtolower(end($temp));
                    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

                    //Validate file extension
                    if(in_array($ext, $allowed_ext))
                    {
                        //Rename the Image
                        $image_name = "Food_Category_".rand(000, 999).'.'.$ext;
                        
                        $source_path = $_FILES['image']['tmp_name'];
                        $destination_path = "../images/category/".$image_name;

                        //Upload the New Image
                        if(move_uploaded_file($source_path, $destination_path))
                        {
                            //Successfully uploaded new image
                            //Remove the old image if it exists
                            if($current_image != "")
                            {
                                $remove_path = "../images/category/".$current_image;
                                
                                //Check if old image file exists before trying to delete
                                if(file_exists($remove_path))
                                {
                                    //Attempt to remove the old image
                                    if(!unlink($remove_path))
                                    {
                                        //Failed to remove old image
                                        $_SESSION['failed-remove'] = "<div class='error'>Failed to remove current image.</div>";
                                    }
                                }
                            }
                        }
                        else
                        {
                            //Failed to upload new image
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload New Image.</div>";
                            header('location:'.SITEURL.'admin/manage-category.php');
                            exit();
                        }
                    }
                    else
                    {
                        //Invalid file type
                        $_SESSION['upload'] = "<div class='error'>Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.</div>";
                        header('location:'.SITEURL.'admin/manage-category.php');
                        exit();
                    }
                }
            }

            //3. Update the Database
            $sql2 = "UPDATE category_table SET 
                title = '$title',
                image_name = '$image_name',
                featured = '$featured',
                active = '$active' 
                WHERE id=$id
            ";

            //Execute the Query
            $res2 = mysqli_query($conn, $sql2);

            //4. Redirect to Manage Category with Message
            if($res2==true)
            {
                //Category Updated
                $_SESSION['update'] = "<div class='success'>Category Updated Successfully.</div>";
                header('location:'.SITEURL.'admin/manage-category.php');
                exit();
            }
            else
            {
                //Failed to update category
                $_SESSION['update'] = "<div class='error'>Failed to Update Category.</div>";
                header('location:'.SITEURL.'admin/manage-category.php');
                exit();
            }
        }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>
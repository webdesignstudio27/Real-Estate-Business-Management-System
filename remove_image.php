<?php
include('connection/connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
/// Check if the 'image' parameter is set
if (isset($_POST['image'])) {
    $image = trim($_POST['image']);
    error_log('Received image: ' . $image); // Log received image

    // Path to the image on the server
    $img_path = 'admin/property_img/property/' . $image;

    // Check if the file exists
    if (file_exists($img_path)) {
        // Delete the image from the server
        unlink($img_path);

        // Update the database to remove the image reference
        $query = "UPDATE properties SET images = REPLACE(images, '$image', '') WHERE FIND_IN_SET('$image', images)";
        
        if (mysqli_query($conn, $query)) {
            echo "Image removed from the server and database.";
        } else {
            echo "Error updating database: " . mysqli_error($conn);
        }
    } else {
        echo "Image not found on the server.";
    }
} else {
    echo "No image specified.";
}
?>
The error_log will log the received image parameter to your server's error log, which will help you confirm if the data is reaching the backend.

Full Example with Adjustments:
Updated Frontend Code (JavaScript and PHP):
php
Copy
Edit
<div class="col-sm-6">
    <label for="images">Upload Images</label>
    <?php
    $images = array_map(function ($img) {
        return trim($img, " '\"");
    }, explode(',', $property['images']));

    foreach ($images as $index => $img) {
        $img_path = "admin/property_img/property/" . $img;
        if (!empty($img)) {
            echo "<div id='img_$index' style='display:inline-block;position:relative;margin:5px;'>
                <img src='$img_path' width='100' height='100' style='border:1px solid #ccc;' onclick=\"removeImage('$img', $index)\">
                <button onclick=\"removeImage('$img', $index)\" 
                        style='position:absolute;top:0;right:0;background:red;color:white;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;'>×</button>
              </div>";
        }
    }
    ?>
    <br>
    <input type="file" id="imageInput" name="images[]" class="form-control" multiple>
    <div id="fileCountDisplay" style="margin-top: 5px; font-weight: bold;"></div>
    <div style="margin-top: 10px;">
        <a href="#" onclick="openFileDialog(event)">Add More..</a>
    </div>
</div>

<script>
function removeImage(imageName, index) {
    console.log('Attempting to remove image:', imageName); // Debugging line

    // Hide the image and button from the UI
    var imgDiv = document.getElementById('img_' + index);
    if (imgDiv) {
        imgDiv.style.display = 'none';
    }

    // Send AJAX request to remove the image from the server and update the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'remove_image.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    // Prepare the data to send
    var data = 'image=' + encodeURIComponent(imageName);
    console.log('Sending data:', data); // Debugging line
    
    // Send the request
    xhr.send(data);

    // Handle the response (Optional)
    xhr.onload = function() {
        if (xhr.status == 200) {
            console.log('Image removed successfully');
            // Optionally, handle any further actions after the image is removed.
        } else {
            console.error('Error removing image');
        }
    };
}
</script>
Backend (PHP - remove_image.php):
php
Copy
Edit
<?php
// Include database connection
include('db_connection.php');

// Check if the 'image' parameter is set
if (isset($_POST['image'])) {
    $image = trim($_POST['image']);
    error_log('Received image: ' . $image); // Log the received image to help debugging

    // Path to the image on the server
    $img_path = 'admin/property_img/property/' . $image;

    // Check if the file exists
    if (file_exists($img_path)) {
        // Delete the image from the server
        unlink($img_path);

        // Update the database to remove the image reference
        $query = "UPDATE properties SET images = REPLACE(images, '$image', '') WHERE FIND_IN_SET('$image', images)";
        
        if (mysqli_query($conn, $query)) {
            echo "Image removed from the server and database.";
        } else {
            echo "Error updating database: " . mysqli_error($conn);
        }
    } else {
        echo "Image not found on the server.";
    }
} else {
    echo "No image specified.";
}
?>
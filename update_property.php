<!DOCTYPE html>
<html lang="en">
<?php
include('connection/connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Initialize property array for form defaults
$property = [
    'property_type' => '',
    'post_type' => '',
    'title' => '',
    'area' => '',
    'bathrooms' => '',
    'bedrooms' => '',
    'price' => '',
    'location' => '',
    'description' => '',
    'images' => '',
    'pdf' => ''
];

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in.'); window.location.href='login.php';</script>";
    exit();
}

$u_id = $_SESSION['user_id'];

if (isset($_GET['property_id'])) {
    $property_id = intval($_GET['property_id']);

    // Get property details
    $stmt = $db->prepare("SELECT * FROM properties WHERE property_id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $property = $result->fetch_assoc();
    } else {
        echo "<script>alert('Property not found.'); window.location.href='home.php';</script>";
        exit();
    }
    $stmt->close();

    // Check user role
    $stmt = $db->prepare("SELECT type FROM users WHERE u_id = ?");
    $stmt->bind_param("i", $u_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user['type'] !== 'seller') {
        echo "<script>alert('Only sellers can update properties!'); window.location.href='home.php';</script>";
        exit();
    }
}

if (isset($_POST['submit'])) {
    $property_id = intval($_GET['property_id']);

    // Form fields
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $property_type = $_POST['property_type'];
    $post_type = $_POST['post_type'];
    $location = htmlspecialchars($_POST['location']);
    $area = intval($_POST['area']);
    $bedrooms = intval($_POST['bedrooms']);
    $bathrooms = intval($_POST['bathrooms']);

    // Handle image upload
    $image_names = [];
    $store_path = "admin/property_img/property/";
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!is_dir($store_path)) {
        mkdir($store_path, 0777, true);
    }

    // Start with existing images
    $image_names = explode(',', $property['images']);

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['images']['name'][$key];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            if (!in_array($image_ext, $allowed_extensions)) {
                echo "<script>alert('Invalid image format. Allowed: jpg, jpeg, png, gif');</script>";
                exit();
            }

            $new_image_name = uniqid() . '.' . $image_ext;
            $image_path = $store_path . $new_image_name;

            if (move_uploaded_file($tmp_name, $image_path)) {
                $image_names[] = $new_image_name;
            }
        }
    }
    // Remove deleted images
    if (!empty($_POST['images_to_remove'])) {
        $to_remove = explode(',', $_POST['images_to_remove']);
        $image_names = array_filter($image_names, function ($img) use ($to_remove, $store_path) {
            if (in_array($img, $to_remove)) {
                $file_path = $store_path . $img;
                if (file_exists($file_path)) {
                    unlink($file_path); // Delete from folder
                }
                return false; // Remove from DB
            }
            return true;
        });
    }


    $image_names_string = implode(',', $image_names);

    // Handle PDF
    $pdf_new_name = $property['pdf'];
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === 0) {
        $pdf_name = $_FILES['pdf']['name'];
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_ext = pathinfo($pdf_name, PATHINFO_EXTENSION);
        $pdf_new_name = uniqid('pdf_') . '.' . $pdf_ext;
        move_uploaded_file($pdf_tmp, $store_path . $pdf_new_name);
    }

    // Update query
    $update_sql = "UPDATE properties 
                   SET title = ?, description = ?, price = ?, property_type = ?, post_type = ?, 
                       location = ?, area = ?, bedrooms = ?, bathrooms = ?, images = ?, pdf = ? 
                   WHERE property_id = ? AND u_id = ?";

    $stmt = $db->prepare($update_sql);
    $stmt->bind_param(
        "ssdsdssisssii",
        $title,
        $description,
        $price,
        $property_type,
        $post_type,
        $location,
        $area,
        $bedrooms,
        $bathrooms,
        $image_names_string,
        $pdf_new_name,
        $property_id,
        $u_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Property updated successfully!'); window.location.href='propertieslist.php';</script>";
    } else {
        echo "<script>alert('Failed to update property.');</script>";
    }

    $stmt->close();
}
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="icon" href="#">
    <title>Home || Real Estate </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>
        .booking-form {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 50px auto;
        }

        .booking-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .booking-btn {

            width: 100%;
            padding: 12px;
            font-size: 18px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .booking-btn:hover {
            background: #0056b3;
        }



        #search-icon {
            right: 30rem;
            cursor: pointer;
            margin-left: 1rem;
            height: 3rem;
            line-height: 3rem;
            width: 3rem;
            text-align: center;
            font-size: 1rem;
            color: var(--black);
            border-radius: 50%;
            background: #eee;
            align-items: right;
        }

        #search-icon:hover {
            color: #fff;
            background: var(--green);
            transform: rotate(360deg);
        }


        header .icons #menu-bars {
            display: none;
        }

        #search-form {
            position: absolute;
            top: 40%;
            /* Align with the middle of the search icon */
            transform: translateY(-50%);
            /* Center vertically */
            right: 5rem;
            /* Adjust to show beside the search icon */
            height: 1.2cm;
            max-width: 280px;
            z-index: 1004;
            background: rgba(228, 225, 225, 0.8);
            display: none;
            /* Initially hidden */
            padding: 10px;
            transition: all 0.4s ease;
            border-radius: 50px;
            margin-top: -20%;
        }

        /* Show search form when active */
        #search-form.active {
            display: flex;
            align-items: center;
            top: 2.5rem;
            min-width: 270px;

        }

        /* Search box styling */
        #search-box {
            width: 100%;
            padding: 10px;
            font-size: 1.3rem;
            border: none;
            color: #000000 !important;
            background: rgb(148, 148, 148);
            border-bottom: 2px solid #052c6f;
            outline: none;
            transition: width 0.4s ease;
            height: 1cm;
            border-radius: 50px;

        }

        #search-box::placeholder {
            color: #030303;
            font-size: smaller;
        }

        #search-box:hover {
            background-color: rgb(68, 99, 123);
            font-size: 1rem;
        }

        #search {
            position: absolute;
            right: 37px;
            top: 15px;
            color: rgb(255, 255, 255);
            font-size: 1rem;
            cursor: pointer;
            display: inline-block;
            /* Ensure it behaves like a block-level element */
        }

        #close {
            position: absolute;
            right: 60px;
            top: 15px;
            color: rgb(255, 255, 255);
            font-size: 1rem;
            cursor: pointer;
            display: inline-block;
            /* Ensure it behaves like a block-level element */
        }

        .custom-submenu {
            position: absolute;
            top: 100%;
            /* Show directly below the profile */
            right: 1;
            /* Align right if the menu is on the right side */
            background-color: transparent;
            border-radius: 8px;
            padding: 10px;
            display: none;
            z-index: 999;
            min-width: 180px;
            min-width: 200px;

        }

        .custom-submenu a {
            display: block;
            color: #000000;
            padding: 8px 10px;
            text-decoration: none;
            white-space: nowrap;
            gap: 10px;
            align-items: left;
        }

        .custom-submenu a:hover {
            background-color: rgba(140, 224, 136, 0.432);
            border-radius: 5px;
        }

        .nav-item:hover .custom-submenu {
            display: block;
        }
    </style>

</head>


<body class="home">
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/Estate_logo.png" alt="" width="18%"> </a>
                <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item">
                            <i class="fas fa-search" id="search-icon"></i>
                            <form id="search-form" action="search_seller.php" method="GET">
                                <input type="search" placeholder="Search here..." name="search" id="search-box" required>
                                <button type="submit" id="search-btn" class="fas fa-search" style="background: none; border: none;"></button>

                                <i class="fas fa-times" id="close"></i>
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="home.php">
                                <img class="img-icon" src="images/Home.png"> Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="propertieslist.php">
                                <img class="img-icon" src="images/property.png"> Properties
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link active" href="createlist.php">
                                <img class="img-icon" src="images/list.png">Create List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="your_bookings.php">
                                <img class="img-icon" src="images/list.png">Bookings
                            </a>
                        </li>

                        <?php
                        if (empty($_SESSION["user_id"])) { // If user is not logged in
                            echo '<li class="nav-item">
                <a href="login.php" class="nav-link active">
                    <img class="img-icon" src="images/login.png"> Login
                </a>
              </li>
              <li class="nav-item">
                <a href="registration.php" class="nav-link active">
                    <img class="img-icon" src="images/register.png"> Register
                </a>
              </li>';
                        } else {
                            echo '
             <li class="nav-item position-relative">
                                    <a href="#" class="nav-link active" id="profileToggle">
                                        <img class="img-icon" src="images/login.png"> Profile
                                    </a>
                                    <div class="custom-submenu" id="profileSubMenu">
                                        <a href="update_seller.php" class="nav-link active">
                                            <img class="img-icon" src="images/edit_user.png"> Update Account
                                        </a>
                                        <a href="logout.php" class="nav-link active">
                                            <img class="img-icon" src="images/logout.png"> Logout
                                        </a>
                                    </div>
                                </li>';
                        }
                        ?>
                    </ul>




                </div>
            </div>
        </nav>

    </header>

    <section class="popular">
        <div class="container">
            <h1>Publish Property</h1>
            <form action="" method="post" enctype="multipart/form-data" id="propertyForm">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="property_type">Property Type</label>
                        <select class="form-control" name="property_type" id="property_type" required>
                            <option value="" disabled>Select Property Type</option>
                            <option value="land" <?php echo ($property['property_type'] == 'land') ? 'selected' : ''; ?>>Land</option>
                            <option value="house" <?php echo ($property['property_type'] == 'house') ? 'selected' : ''; ?>>House</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="post_type">Type Of Post</label>
                        <select class="form-control" name="post_type" id="post_type" required>
                            <option value="" disabled>Select Post Type</option>
                            <option value="sale" <?php echo ($property['post_type'] == 'sale') ? 'selected' : ''; ?>>Sale</option>
                            <option value="rent" <?php echo ($property['post_type'] == 'rent') ? 'selected' : ''; ?>>Rent</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="title">Property Name</label>
                        <input class="form-control" type="text" name="title" id="title" value="<?php echo htmlspecialchars($property['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span id="title-error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-6">
                        <label for="area">Area (sq ft)</label>
                        <input class="form-control" type="number" name="area" id="area" value="<?php echo htmlspecialchars($property['area'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span id="area-error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-6">
                        <label for="bathrooms">Bathrooms</label>
                        <input class="form-control" type="number" name="bathrooms" id="bathrooms" value="<?php echo htmlspecialchars($property['bathrooms'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="bedrooms">Bedrooms</label>
                        <input class="form-control" type="number" name="bedrooms" id="bedrooms" value="<?php echo htmlspecialchars($property['bedrooms'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="price">Price</label>
                        <input class="form-control" type="number" name="price" id="price" value="<?php echo htmlspecialchars($property['price'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span id="price-error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-6">
                        <label for="location">Location</label>
                        <input class="form-control" type="text" name="location" id="location" value="<?php echo htmlspecialchars($property['location'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span id="location-error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-6">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" required><?php echo htmlspecialchars($property['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <span id="description-error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-6">
                        <label for="images">Upload Images</label>

                        <!-- Hidden input to track removed images -->
                        <input type="hidden" name="images_to_remove" id="images_to_remove" value="">

                        <div id="existingImages">
                            <?php
                            $images = array_map(function ($img) {
                                return trim($img, " '\"");
                            }, explode(',', $property['images']));

                            foreach ($images as $img) {
                                $img_path = "admin/property_img/property/" . $img;
                                if (!empty($img)) {
                                    echo "
                <div class='image-preview' data-img='$img' style='position:relative; display:inline-block; margin:5px;'>
                    <img src='$img_path' width='100' height='100' style='border:1px solid #ccc;'>
                    <span onclick='removeImage(this)' 
                          style='position:absolute; top:0; right:0; background:red; color:white; padding:2px 6px; cursor:pointer;'>×</span>
                </div>";
                                }
                            }
                            ?>
                        </div>

                        <br>
                        <!-- Main file input (will be updated dynamically) -->
                        <input type="file" id="imageInput" name="images[]" class="form-control" multiple>

                        <!-- File count display -->
                        <div id="fileCountDisplay" style="margin-top: 5px; font-weight: bold;"></div>

                        <!-- Add more link -->
                        <div style="margin-top: 10px;">
                            <a href="#" onclick="openFileDialog(event)">Add More..</a>
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <label for="pdf">Upload PDF</label>
                        <?php if (!empty($property['pdf'])): ?>
                            <a href="admin/property_img/property/<?php echo $property['pdf']; ?>" target="_blank">View Existing PDF</a><br>
                        <?php endif; ?>
                        <input type="file" name="pdf" id="pdf" class="form-control">
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" name="submit" class="btn btn-primary">Publish Property</button>
                        <div style="margin-top: 10px;">
                            <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        let allSelectedFiles = [];

        function openFileDialog(event) {
            event.preventDefault();

            const tempInput = document.createElement("input");
            tempInput.type = "file";
            tempInput.accept = "image/*";
            tempInput.multiple = true;

            tempInput.addEventListener("change", () => {
                const newFiles = Array.from(tempInput.files);
                allSelectedFiles = allSelectedFiles.concat(newFiles);
                updateMainInput();
            });

            tempInput.click();
        }

        function updateMainInput() {
            const dataTransfer = new DataTransfer();

            allSelectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            const imageInput = document.getElementById("imageInput");
            imageInput.files = dataTransfer.files;

            document.getElementById("fileCountDisplay").textContent =
                `${allSelectedFiles.length} file${allSelectedFiles.length > 1 ? 's' : ''}`;
        }

        document.getElementById("imageInput").addEventListener("change", function() {
            allSelectedFiles = Array.from(this.files);
            updateMainInput();
        });

        function removeImage(span) {
            const container = span.parentElement;
            const imgName = container.getAttribute('data-img');

            container.remove();

            const input = document.getElementById("images_to_remove");
            let current = input.value ? input.value.split(',') : [];
            if (!current.includes(imgName)) {
                current.push(imgName);
            }
            input.value = current.join(',');
        }

        function toggleFields() {
            const propertyType = document.getElementById('property_type').value;
            const bedroomsField = document.getElementById('bedrooms');
            const bathroomsField = document.getElementById('bathrooms');

            if (propertyType === 'land') {
                bedroomsField.disabled = true;
                bathroomsField.disabled = true;
                bedroomsField.value = '0';
                bathroomsField.value = '0';
            } else {
                bedroomsField.disabled = false;
                bathroomsField.disabled = false;
                bedroomsField.value = '';
                bathroomsField.value = '';
            }
        }

        document.getElementById('property_type').addEventListener('change', toggleFields);

        // Call it on page load in case "land" is pre-selected
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const postType = document.getElementById("post_type");
            const priceLabel = document.querySelector("label[for='price']");
            const pdfInput = document.getElementById("pdf");

            function toggleFields() {
                if (postType.value === "rent") {
                    priceLabel.textContent = "Rent Amount";
                    pdfInput.disabled = true;
                    pdfInput.value = ""; // clear the file input if needed
                    pdfInput.parentElement.style.opacity = "0.5"; // visually show it's disabled
                } else {
                    priceLabel.textContent = "Price";
                    pdfInput.disabled = false;
                    pdfInput.parentElement.style.opacity = "1";
                }
            }

            // Call on page load
            toggleFields();

            // Attach change event
            postType.addEventListener("change", toggleFields);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fields = ['title', 'price', 'area', 'location', 'description'];

            fields.forEach(field => {
                const input = document.getElementById(field);
                const errorSpan = document.getElementById(field + '-error');

                if (input) {
                    input.addEventListener('input', function() {
                        const value = input.value;

                        // Send AJAX POST request
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'property_validate.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                errorSpan.textContent = xhr.responseText;
                            }
                        };
                        xhr.send('field=' + encodeURIComponent(field) + '&value=' + encodeURIComponent(value));
                    });
                }
            });
        });
    </script>



    <?php include "include/footer.php" ?>


    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    <script src="js/jquery.js"></script>

    <script src="js/jquerys.js"></script>
</body>

</html>
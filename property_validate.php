<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $field = $_POST['field'];
    $value = $_POST['value'];
    
    switch ($field) {
        case 'title':
            if (!preg_match("/^[A-Za-z\s]{5,15}$/", $value)) {
                echo "Property Name must be between 5 and 15 letters.";
            } else {
                echo ""; // No error
            }
            break;
        case 'price':
            if ($value <= 1000) {
                echo "Price must be greater than 1000.";
            } else {
                echo ""; // No error
            }
            break;
        case 'area':
            if ($value <= 0) {
                echo "Area must be greater than 0.";
            } else {
                echo ""; // No error
            }
            break;
        case 'location':
            $locationPattern = "/^[A-Za-z0-9\s,]+, [A-Za-z\s]+, [A-Za-z\s]+, [A-Za-z\s]+, [A-Za-z\s]+ - \d{6}$/";
            if (!preg_match($locationPattern, $value)) {
                echo "Location must be in the format: Plot no., Street, Place, District, State - Pincode.";
            } else {
                echo ""; // No error
            }
            break;
        case 'description':
            if (strlen($value) < 10) {
                echo "Description must be at least 10 characters long.";
            } else {
                echo ""; // No error
            }
            break;
    }
}
?>

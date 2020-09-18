<?php

//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions


// SESSION
session_start();

$addEmail = $_SESSION['email'] = $_POST['email'];
$addStreet = $_SESSION['street'] = $_POST['street'];
$addStreetNumber = $_SESSION['streetnumber'] = $_POST['streetnumber'];
$addCity = $_SESSION['city'] = $_POST['city'];
$addZipcode = $_SESSION['zipcode'] = $_POST['zipcode'];

echo "<b>Email: </b>" . $addEmail . "<br />";
echo "<b>Street: </b>" . $addStreet . " ";
echo $addStreetNumber . "<br />";
echo "<b>city: </b>" . $addCity . "<br />";
echo "<b>Zipcode: </b>" . $addZipcode . "<br />";



// DATA FROM FORM + CHECK
$emailErr = $streetErr = $streetNumberErr = $cityErr = $zipcodeErr = $productsErr = "";
$email =  $street = $streetNumber = $city = $zipcode = $products = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = checkInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // street
    if (empty($_POST["street"])) {
        $streetErr = "street is required";
    } else {

        $street = checkInput($_POST["street"]);

        if (!preg_match("/^[a-zA-Z-' ]*$/", $street)) {
            $streetErr = "Only letters and white space allowed";
        }
    }

    // streetnumber
    if (empty($_POST["streetnumber"])) {
        $streetNumberErr = "streetnumber is required";
    } else {

        $streetNumber = checkInput($_POST["streetnumber"]);

        if (!preg_match("/^[1-9][0-9]*$/", $streetNumber)) {
            $streetNumberErr = "Only numbers allowed";
        }
    }

    // city
    if (empty($_POST["city"])) {
        $cityErr = "city is required";
    } else {

        $city = checkInput($_POST["city"]);

        if (!preg_match("/^[a-zA-Z-' ]*$/", $city)) {
            $cityErr = "Only letters allowed";
        }
    }

    // zipcode
    if (empty($_POST["zipcode"])) {
        $zipcodeErr = "zipcode is required";
    } else {

        $zipcode = checkInput($_POST["zipcode"]);

        if (!preg_match("/^[1-9][0-9]*$/", $zipcode)) {
            $zipcodeErr = "Only numbers allowed";
        }
    }
}

// CHECK DATAT INPUT
function checkInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



// Switch between drinks and food
// your products with their price
if ($_GET['food'] == 1) {
    $products = [
        ['name' => 'Club Ham', 'price' => 3.20],
        ['name' => 'Club Cheese', 'price' => 3],
        ['name' => 'Club Cheese & Ham', 'price' => 4],
        ['name' => 'Club Chicken', 'price' => 4],
        ['name' => 'Club Salmon', 'price' => 5]
    ];
} else {
    $products = [
        ['name' => 'Troubadour Magma', 'price' => 1],
        ['name' => 'Jupiler', 'price' => 5],
        ['name' => 'St. Bernardus', 'price' => 6],
        ['name' => 'Ice-tea', 'price' => 3],
        ['name' => 'Duvel', 'price' => 4]

    ];
}


// TOTAL VALUE

$totalValue = 0;
foreach ($_POST['products'] as $i => $product) {
    $totalValue += ($products[$i]['price']);
}


// TOTAL FORM VALIDATION
if (!empty($_POST["email"]) && !empty($_POST["street"]) && !empty($_POST["streetnumber"]) && !empty($_POST["city"]) && !empty($_POST["zipcode"])) {
    $result = '<div class="alert alert-success" role="alert">Your order is submitted, thank you</div>';
} else {
    $result = '<div class="alert alert-danger" role="alert">Please fill in all required fields</div>';
}

//ASSIGNS TOTAL PRICE TO COOKIE

if (isset($_POST['totalValue']))
    setcookie('totalValue', $totalValue, time() + 60 * 60 * 7);


//MAIL STUFF
if (isset($_POST['submit'])) {
    $mailto = "bwa_jha@hotmail.com";
    $subject = "Order #xx from the Personal Ham Processors";
    $message = "This is a testmail";
    $header = "From:Personal Ham Processors";
    mail($mailto, $subject, $message, $header);
    if (mail($mailto, $subject, $message, $header)) {
        echo "mail sent";
    } else {
        echo "<div class=\"alert alert-warning\" role=\"alert\"> An error has occured, mail not sent! </div>";
    }
}
$deliveryTime = estimateDeliveryTime();

$deliveryTime = "";
// Delivery time
function estimateDeliveryTime()
{
    $timeNow = date("H:i");

    if (!empty($_POST["expressDelivery"])) {
        $hourExpressDel = date('H:i', strtotime('+45 minutes', strtotime($timeNow)));
        return "Your order will be delivered at " . $hourExpressDel . "</br>";
    } else {
        $hourNormalDel = date('H:i', strtotime('+2 hours', strtotime($timeNow)));
        return "Your order will be delivered at " . $hourNormalDel . "</br>";
    };
}
// CHECK WHATS HAPPENING
function whatshappening()
{
    echo '<h3>$_POST</h3>';
    var_dump($_POST['products']);
    echo '<h3>$_COOKIE</h3>';
    var_dump($_COOKIE);
    echo '<h3>$_SESSION</h3>';
    var_dump($_SESSION);
}
// whatIsHappening();

// Link to form page
require 'form-view.php';



// if (isset($_POST['submit'])) {
//     echo 123;
// }


// foreach ($_POST as $key => $value) {
//     $_SESSION['POST'][$key] = $value;
// }

// echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

// var_dump($_POST["name"]);
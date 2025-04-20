<?php

$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fullname = $_POST['fullname'];
$username = $_POST['username'];
$password = $_POST['password'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$address = $_POST['address'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$bmi = ($height > 0) ? round($weight / (($height / 100) ** 2), 2) : null;
$emergencyLast = $_POST['emergencyLast'];
$emergencyFirst = $_POST['emergencyFirst'];
$emergencyMobile = $_POST['emergencyMobile'];
$relationship = $_POST['relationship'];


$stmt = $conn->prepare("INSERT INTO users (
    full_name, username, password_hash, dob, gender, email, mobile, address,
    height_cm, weight_kg, bmi, emergency_lastname, emergency_firstname, emergency_mobile, relationship
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssssssdddsiss",
    $fullname, $username, $password, $dob, $gender, $email, $mobile, $address,
    $height, $weight, $bmi, $emergency_lastname, $emergency_firstname, $emergency_mobile, $relationship
);

if ($stmt->execute()) {
    header("Location: terms.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

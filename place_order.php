<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Retrieve the raw POST data
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received or invalid JSON."]);
    exit;
}
// Debug received data
error_log(print_r($data, true));

// Extract user and order details
$fullName = $data['fullName'];
$email = $data['email'];
$phone = $data['phone'];
$address = $data['address'];
$pincode = $data['pincode'];
$flatno = $data['flatno'];
$paymentMethod = $data['paymentMethod'];
$cartItems = $data['cartItems'];

// Compose the email
$sellerEmail = "seller@example.com"; // Replace with the seller's email address
$subject = "New Order Received";

$message = "New order received from $fullName.\n\n";
$message .= "Contact Details:\n";
$message .= "Name: $fullName\n";
$message .= "Email: $email\n";
$message .= "Phone: $phone\n";
$message .= "Address: $flatno, $address, $pincode\n";
$message .= "Payment Method: $paymentMethod\n\n";
$message .= "Order Details:\n";

$totalPrice = 0;

foreach ($cartItems as $item) {
    $itemTotal = $item['quantity'] * floatval($item['price']);
    $totalPrice += $itemTotal;

    $message .= "Item: " . $item['name'] . "\n";
    $message .= "Description: " . ($item['description'] ?? 'N/A') . "\n";
    $message .= "Quantity: " . $item['quantity'] . "\n";
    $message .= "Price: $" . $itemTotal . "\n\n";
}

$message .= "Total Price: $" . $totalPrice . "\n";

// Send email
$headers = "From: no-reply@yourwebsite.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8";

if (mail($sellerEmail, $subject, $message, $headers)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to send email."]);
}

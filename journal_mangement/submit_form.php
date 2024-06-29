<?php
include 'config/db.php'; // Include your database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $subject);
    $stmt->bindParam(4, $message);

    // Set parameters and execute
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $stmt->execute();

    // Show popup message (you can customize this further)
    echo "<script>alert('Form submitted successfully!'); window.location.href = 'index.php';</script>";
}
?>

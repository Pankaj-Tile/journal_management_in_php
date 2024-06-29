
<?php
session_start();
require '../config/db.php';

$registration_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['role'] === 'reviewer') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $profile_image = $_FILES['profile_image']['name'];
    $target_dir = "../uploads/profile_images/";
    $target_file = $target_dir . basename($profile_image);

    move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);

    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role, profile_image) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$username, $email, $password, 'reviewer', $profile_image]);

    $registration_message = "Registration successful!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviewer Register</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/form.css">
    <style>
.custom-form-container {
    min-width: 500px;
    max-width: 600px;
    margin: 30px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.custom-form-header {
    margin-bottom: 20px;
    text-align: center;
    color: #333333;
}

.custom-form-group {
    margin-bottom: 15px;
}

.custom-form-group label {
    font-weight: bold;
    color: #555555;
}

.custom-form-control {
    display: block;
    width: 100%;
    padding: 10px;
    font-size: 16px;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 4px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-form-control:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.custom-file-input {
    margin-top: 10px;
    margin-bottom: 10px;
}

.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-top: 10px;
}

.custom-submit-btn {
    width: 100%;
    padding: 10px;
    font-size: 18px;
    color: #fff;
    background-color: #d92cf9;
    border-color: #d92cf9;
    border-radius: 5px;
    cursor: pointer;
}

.custom-submit-btn:hover {
    background-color:rgba(210, 103, 155, 0.8);
    border-color: rgba(210, 103, 155, 0.8);
}

@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}
body{
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #d92cf9;
}
</style>
</head>
<body>
<div class="custom-form-container">
    <h2 class="custom-form-header">Reviewer Registration</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="custom-form-group">
            <label for="username" class="custom-label">Username</label>
            <input type="text" class="custom-form-control" id="username" name="username" required>
        </div>
        <div class="custom-form-group">
            <label for="email" class="custom-label">Email address</label>
            <input type="email" class="custom-form-control" id="email" name="email" required>
        </div>
        <div class="custom-form-group">
            <label for="password" class="custom-label">Password</label>
            <input type="password" class="custom-form-control" id="password" name="password" required>
        </div>
        <div class="custom-form-group">
            <label for="profile_image" class="custom-label">Profile Image</label>
            <input type="file" class="custom-file-input" id="profile_image" name="profile_image" required>
        </div>
        <input type="hidden" name="role" value="reviewer">
        <button type="submit" class="custom-submit-btn">Register</button>
    </form>
</div>
</body>
</html>

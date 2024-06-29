<?php
session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'author') {
    header('Location: ../login.php');
    exit();
}
$registration_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $author_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $manuscript_name = $_POST['manuscript_name'];
    $manuscript_reference = $_POST['manuscript_reference'];
    $manuscript_file = $_FILES['manuscript_file']['name'];
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/journal_mangement/uploads/manuscripts/";

    $target_file = $target_dir . basename($manuscript_file);

    move_uploaded_file($_FILES['manuscript_file']['tmp_name'], $target_file);

    $stmt = $pdo->prepare('INSERT INTO manuscripts (title, abstract, file_path, author_id, status) VALUES (?, ?, ?, ?, "submitted")');
    $stmt->execute([$title, $abstract, $manuscript_file, $author_id]);
    $registration_message = "Manuscript submitted successfully!";
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Manuscript</title>
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

    <h2 class="custom-form-header">Submit Manuscript</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="custom-form-group">
            <label for="title">Manuscript Title</label>
            <input type="text" class="custom-form-control" id="title" name="title" required>
        </div>
        <div class="custom-form-group">
            <label for="abstract">Abstract</label>
            <textarea class="custom-form-control" id="abstract" name="abstract" rows="3" required></textarea>
        </div>
        <div class="custom-form-group">
            <label for="manuscript_name">Manuscript Name</label>
            <input type="text" class="custom-form-control" id="manuscript_name" name="manuscript_name" required>
        </div>
        <div class="custom-form-group">
            <label for="manuscript_reference">Manuscript Reference</label>
            <input type="text" class="custom-form-control" id="manuscript_reference" name="manuscript_reference" required>
        </div>
        <div class="custom-form-group">
            <label for="manuscript_file">Manuscript Document</label>
            <input type="file" class="custom-form-control" id="manuscript_file" name="manuscript_file" required>
        </div>
        <button type="submit" class="custom-submit-btn">Submit</button>
    </form>
    <?php if ($registration_message != ''): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $registration_message; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

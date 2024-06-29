<?php
// ... [Your existing PHP code] ...

session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle the status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'], $_POST['manuscript_id'])) {
    $status = $_POST['status'];
    $manuscript_id = $_POST['manuscript_id'];

    // Update manuscript status
    $stmt = $pdo->prepare('UPDATE manuscripts SET status = ? WHERE id = ?');
    $stmt->execute([$status, $manuscript_id]);

    echo "Manuscript status updated successfully!";
}

// Fetch only published manuscripts
$stmt = $pdo->prepare('SELECT m.*, u.username AS author FROM manuscripts m JOIN users u ON m.author_id = u.id WHERE m.status = "accepted"');
$stmt->execute();
$publishedManuscripts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Abstract</th>
                <th>Status</th>
                <th>Action</th>
                <th>View Document</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($publishedManuscripts as $manuscript): ?>
                <tr>
                    <td><?php echo htmlspecialchars($manuscript['title']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['author']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['abstract']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['status']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="manuscript_id" value="<?php echo $manuscript['id']; ?>">
                            <select name="status" class="form-control">
                                <option value="published" <?php if ($manuscript['status'] == 'published') echo 'selected'; ?>>Published</option>
                                <option value="rejected" <?php if ($manuscript['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </form>
                    </td>
                    <td>
                        <a href="../uploads/manuscripts/<?php echo htmlspecialchars($manuscript['file_path']); ?>" target="_blank">View Document</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

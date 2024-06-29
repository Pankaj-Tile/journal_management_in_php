<?php
session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'reviewer') {
    header('Location: ../login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'], $_POST['manuscript_id'], $_POST['comments'])) {
    $status = $_POST['status'];
    $manuscript_id = $_POST['manuscript_id'];
    $comments = $_POST['comments'];
    $reviewer_id = $_SESSION['user_id'];

    // Insert new review
    $stmt = $pdo->prepare('INSERT INTO reviews (manuscript_id, reviewer_id, comments, recommendation) VALUES (?, ?, ?, ?)');
    $stmt->execute([$manuscript_id, $reviewer_id, $comments, $status]);

    // Update manuscript status
    $stmt = $pdo->prepare('UPDATE manuscripts SET status = ? WHERE id = ?');
    $stmt->execute([$status, $manuscript_id]);

    // Redirect to the same page to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch manuscripts that are not published, accepted, or rejected, in descending order
$stmt = $pdo->query('SELECT m.*, u.username AS author FROM manuscripts m JOIN users u ON m.author_id = u.id WHERE m.status NOT IN ("published", "accepted", "rejected") ORDER BY m.id DESC LIMIT 5');
$manuscripts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Journals</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="overflow-y: scroll; height: 800px;">
    <h2>Manage Journals</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Abstract</th>
                <th>Status</th>
                <th>Comments & Action</th>
                <th>View Document</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($manuscripts as $manuscript): ?>
                <tr>
                    <td><?php echo htmlspecialchars($manuscript['title']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['author']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['abstract']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['status']); ?></td>
                    <td>
                        <?php
                        // Fetch all comments for the manuscript
                        $comment_stmt = $pdo->prepare('SELECT comments FROM reviews WHERE manuscript_id = ? ORDER BY id DESC');
                        $comment_stmt->execute([$manuscript['id']]);
                        $comments = $comment_stmt->fetchAll();
                        foreach ($comments as $comment) {
                            echo '<div>' . htmlspecialchars($comment['comments']) . '</div>';
                        }
                        ?>
                        <!-- Textarea for new comment -->
                        <form method="POST" action="">
                            <textarea name="comments" class="form-control" rows="3"></textarea>
                            <input type="hidden" name="manuscript_id" value="<?php echo $manuscript['id']; ?>">
                            <select name="status" class="form-control">
                                <option value="submitted" <?php if ($manuscript['status'] == 'submitted') echo 'selected'; ?>>Submitted</option>
                                <option value="in_review" <?php if ($manuscript['status'] == 'in_review') echo 'selected'; ?>>In Review</option>
                                <option value="revised" <?php if ($manuscript['status'] == 'revised') echo 'selected'; ?>>Revised</option>
                                <option value="accepted" <?php if ($manuscript['status'] == 'accepted') echo 'selected'; ?>>Accepted</option>
                                <option value="rejected" <?php if ($manuscript['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
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

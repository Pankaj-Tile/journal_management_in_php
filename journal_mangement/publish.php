<?php
require 'config/db.php';

// Fetch published manuscripts with author and reviewers information
$stmt = $pdo->prepare('SELECT m.id, m.title, m.abstract, m.file_path, u.username AS author, GROUP_CONCAT(r2.username SEPARATOR ", ") AS reviewers
                       FROM manuscripts m
                       JOIN users u ON m.author_id = u.id
                       LEFT JOIN reviews r ON m.id = r.manuscript_id
                       LEFT JOIN users r2 ON r.reviewer_id = r2.id
                       WHERE m.status = "published"
                       GROUP BY m.id');
$stmt->execute();
$publishedManuscripts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Published Journals</title>
   
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Include your custom CSS -->
    <style>
        /* Add your custom styles here */
        .table tbody td:nth-child(odd) {
            background: #f4f6fc;
            border-bottom: 2px solid #eceffa;
        }
        .table tbody th,
        .table tbody td {
            border: none;
            padding: 30px;
            font-size: 14px;
            background: #fff;
            vertical-align: middle;
            border-bottom: 2px solid #f8f9fd;
        }
        /* Add more custom styles as needed */
    </style>
</head>
<body>
<div class="container">
    <h2>Published Journals</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Reviewers</th>
                <th>Abstract</th>
                <th>View File</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($publishedManuscripts as $manuscript): ?>
                <tr>
                    <td><?php echo htmlspecialchars($manuscript['title']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['author']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['reviewers']); ?></td>
                    <td><?php echo htmlspecialchars($manuscript['abstract']); ?></td>
                    <td>
                        <a href="../journal_mangement/uploads/manuscripts/<?php echo htmlspecialchars($manuscript['file_path']); ?>" target="_blank">View File</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

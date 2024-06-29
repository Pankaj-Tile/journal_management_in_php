<?php
session_start();
require '../config/db.php';

// Redirect if not logged in as a reviewer
if ($_SESSION['role'] != 'reviewer') {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Fetch reviews for the logged-in reviewer, ordered by creation date in descending order
$stmt = $pdo->prepare('SELECT m.*, r.reviewer_id, r.comments, r.recommendation, r.created_at
                       FROM manuscripts m
                       JOIN reviews r ON m.id = r.manuscript_id
                       WHERE r.reviewer_id = ?
                       ORDER BY r.created_at DESC');
$stmt->execute([$userId]);
$reviews = $stmt->fetchAll();

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reviewer Dashboard</title>
    <!-- Include Bootstrap CSS and other stylesheets -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- Add your custom styles here -->
    <!--font-awesome.min.css-->
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

    <!--flat icon css-->
    <link rel="stylesheet" href="../assets/css/flaticon.css">

    <!--animate.css-->
    <link rel="stylesheet" href="../assets/css/animate.css">

    <!--owl.carousel.css-->
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/owl.theme.default.min.css">

    <!--bootstrap.min.css-->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <!-- bootsnav -->
    <link rel="stylesheet" href="../assets/css/bootsnav.css">

    <!--style.css-->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!--responsive.css-->
    <link rel="stylesheet" href="../assets/css/responsive.css">

    <style>
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            float: left;
            /* Align image to the left */
            margin-right: 20px;
            /* Add some space between image and buttons */
        }

        .profile-actions {
            padding-top: 110px;
            /* Align buttons with the bottom of the image */
        }

        .profile-actions button {
            margin-right: 50px;
            display: block;
            width: fit-content;
            margin-bottom: 10px;
            /* Add some space between buttons */
        }

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
        <div class="row">
            <div class="col-md-2 text-center">
                <img src="../uploads/profile_images/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="profile-image">
                <div class="profile-actions">
                    <p><a href="../edit_profile.php"><?php echo htmlspecialchars($user['username']); ?></a></p>
                    <a href="">
                        <form method="post">
                            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                        </form>
                    </a>
                    <a href="manage_journal.php"><button class="btn btn-primary"> Review Journals</button></a>
                </div>

            </div>

            <!-- Start Header Navigation -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#">Reviewer Dashboard</a>
            </div><!--/.navbar-header-->
            <!-- End Header Navigation -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="smooth-menu active"></li>
                    <li class="smooth-menu"><a href="#about">Reviewer Role</a></li>
                    <li class="smooth-menu"><a href="#workflow">WorkFlow for Reviewer</a></li>
                    <li class="smooth-menu"><a href="#reviews">Your Reviews</a></li>
                    <li class="smooth-menu"><a href="#contact">Contact</a></li>
                    <li><a href="../publish.php">Journals</a></li>



                </ul><!--/.nav -->
            </div><!-- /.navbar-collapse -->
        </div><!--/.container-->
        </nav><!--/nav-->
        <!-- End Navigation -->
    </div><!--/.header-area-->


    <div id="reviews">
        <div class="section-heading text-center">
            <h2>Your Reviews</h2>
        </div>
        <div style="max-height: 600px; overflow-y: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Abstract</th>
                        <th>Comments</th>
                        <th>Reviewed On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review['title']); ?></td>
                            <td><?php echo htmlspecialchars($review['abstract']); ?></td>
                            <td><?php echo htmlspecialchars($review['comments']); ?></td>
                            <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>



    <section id="about" class="about">
        <div class="section-heading text-center">
            <h2>Reviewer Role</h2>
        </div>
        <div class="container">
            <div class="about-content">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="single-about-txt">
                            <h3>
                                Role Overview:
                                Reviewers in a journal management system are experts in specific fields who evaluate the quality, originality, and significance of manuscripts submitted by authors. Their assessments help ensure the integrity and quality of the journal.

                            </h3>
                            <p>
                                Key Responsibilities:

                                Manuscript Evaluation:

                                Reviewers conduct thorough evaluations of assigned manuscripts, assessing their methodological soundness, originality, and relevance to the journal's scope.
                                Provide constructive feedback and recommendations for improvements.
                                Double-Blind Review:

                                Participate in a double-blind review process, where both the reviewer and the author remain anonymous to each other to maintain objectivity.
                                Recommendation:

                                Based on their evaluations, reviewers recommend whether a manuscript should be accepted, revised, or rejected.
                                Provide detailed comments and suggestions to guide authors in improving their work.
                                Timeliness:

                                Complete reviews within the specified timeframes to ensure the review process remains efficient and timely. </p>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="single-about-add-info">
                                        <h3>phone</h3>
                                        <p>987-123-6547</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="single-about-add-info">
                                        <h3>email</h3>
                                        <p>browny@info.com</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="single-about-add-info">
                                        <h3>website</h3>
                                        <p>www.brownsine.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-1 col-sm-5">
                        <div class="single-about-img">
                            <img src="../assets/images/about/reviwer.jpg" alt="profile_image">
                            <div class="about-list-icon">
                                <ul>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-facebook" aria-hidden="true"></i>
                                        </a>
                                    </li><!-- / li -->
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-dribbble" aria-hidden="true"></i>
                                        </a>

                                    </li><!-- / li -->
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-twitter" aria-hidden="true"></i>
                                        </a>

                                    </li><!-- / li -->
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                                        </a>
                                    </li><!-- / li -->
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-instagram" aria-hidden="true"></i>
                                        </a>
                                    </li><!-- / li -->


                                </ul><!-- / ul -->
                            </div><!-- /.about-list-icon -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section><!--/.about-->
    <!--about end -->



    <!-- Carousel wrapper -->
    <div id="workflow>


        <!-- Controls -->
        <div class="d-flex justify-content-center mb-4">
            <div class="section-heading text-center">
                <h2>Reviewer WorkFlow And Functionality</h2>
            </div>

        </div>
        <!-- Inner -->
        <div class="carousel-inner py-4">
            <!-- Single item -->
            <div class="carousel-item active">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <img src="../assets/images/about/reviwer-home.jpg" class="card-img-top" alt="Waterfall" />
                                <div class="card-body">
                                    <h5 class="card-title">This image shows the home page of a reviewer. <br> <br>
                                </h5>
                                    <p class="card-text">
                                        Key elements include: <br>
                                        1. Profile Picture: A circular profile picture of the reviewer. <br>
                                        2.Email: User name of the reviewer with edit profile functionality which provide form to edit username, email and profile picture <br>
                                        3.Logout Button: A red "Logout" button of exit form the session.  <br>
                                        4.Review Journal button: This button redirect to the review journal form. On that page the manuscript is accepted,rejected,revised,review
                                        and also comment is made on the manuscripts. <br>
                                    </p>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 d-none d-lg-block">
                            <div class="card">
                                <img src="../assets/images/about/reviwer-nav.jpg" class="card-img-top" alt="Sunset Over the Sea" />
                                <div class="card-body">
                                    <h5 class="card-title"> This image displays the top navigation menu for a reviewer. <br> <br></h5>
                                    <p class="card-text">
                                        1.Reviewer Role: It defines the actions of reviewer. <br>
                                        2.WorkFlow for Reviewer : This define the workflow for reviewer. <br>
                                        3.Your Reviews : The review made by the reviwer is display in this session along with comment on the submission made by author. <br>
                                        4.Contact : This is contact form by which reviwer can send message to admin <br>
                                        5.Journals : This is the page where all the published journal are displayed. <br>
                                    </p>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 d-none d-lg-block">
                            <div class="card">
                                <img src="../assets/images/about/review.jpg" class="card-img-top" alt="Sunset over the Sea" />
                                <div class="card-body">
                                    <h5 class="card-title">This image displays a section of a table listing journal manuscripts for review. <br> <br></h5>
                                    <p class="card-text">
                                        The columns include: <br>
                                        Title: Where name of journal is define <br>
                                        Author: where name of journal author is define <br>
                                        Abstract: where abstract about journal is define <br>
                                        Status: where status about journal is define
                                        Comments & Action: Shows an existing comment "ok add more" and a text area for adding more comments. Below the comment box is a dropdown menu with the options: <br>
                                        Submitted: Journal is submitted by the user. <br>
                                        In Review: If reviewer is reviewing journal set this status with comments <br>
                                        Revised: If reviewer want any change select this status and mention in comments what change must be done. <br>
                                        Accepted: If journal is acceptable with no further correction then set this status <br>
                                        Rejected: If reviewer find <br>
                                        View Document: A link to "View Document" for accessing the manuscript. <br>
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- Carousel wrapper -->

        <!--contact start -->
        <section id="contact" class="contact">
            <div class="section-heading text-center">
                <h2>Contact Us</h2>
            </div>
            <div class="container">
                <div class="contact-content">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-5 col-sm-6">
                            <div class="single-contact-box">
                                <div class="contact-form">
                                    <form action="../submit_form.php" method="post">
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="name" placeholder="Name*" name="name" required>
                                                </div><!--/.form-group-->
                                            </div><!--/.col-->
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" id="email" placeholder="Email*" name="email" required>
                                                </div><!--/.form-group-->
                                            </div><!--/.col-->
                                        </div><!--/.row-->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="subject" placeholder="Subject" name="subject" required>
                                                </div><!--/.form-group-->
                                            </div><!--/.col-->
                                        </div><!--/.row-->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <textarea class="form-control" rows="8" id="comment" placeholder="Message" required></textarea>
                                                </div><!--/.form-group-->
                                            </div><!--/.col-->
                                        </div><!--/.row-->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="single-contact-btn">
                                                    <button type="submit" class="btn contact-btn">Contact</button>
                                                </div><!--/.single-single-contact-btn-->
                                            </div><!--/.col-->
                                        </div><!--/.row-->
                                    </form><!--/form-->
                                </div><!--/.contact-form-->
                            </div><!--/.single-contact-box-->
                        </div><!--/.col-->
                        <div class="col-md-offset-1 col-md-5 col-sm-6">
                            <div class="single-contact-box">
                                <div class="contact-adress">
                                    <div class="contact-add-head">
                                        <h3>browny star</h3>
                                        <p>uI/uX designer</p>
                                    </div>
                                    <div class="contact-add-info">
                                        <div class="single-contact-add-info">
                                            <h3>phone</h3>
                                            <p>987-123-6547</p>
                                        </div>
                                        <div class="single-contact-add-info">
                                            <h3>email</h3>
                                            <p>browny@info.com</p>
                                        </div>
                                        <div class="single-contact-add-info">
                                            <h3>website</h3>
                                            <p>www.brownsine.com</p>
                                        </div>
                                    </div>
                                </div><!--/.contact-adress-->
                                <div class="hm-foot-icon">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li><!--/li-->
                                        <li><a href="#"><i class="fa fa-dribbble"></i></a></li><!--/li-->
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li><!--/li-->
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li><!--/li-->
                                        <li><a href="#"><i class="fa fa-instagram"></i></a></li><!--/li-->
                                    </ul><!--/ul-->
                                </div><!--/.hm-foot-icon-->
                            </div><!--/.single-contact-box-->
                        </div><!--/.col-->
                    </div><!--/.row-->
                </div><!--/.contact-content-->
            </div><!--/.container-->

        </section><!--/.contact-->
        <!--contact end -->

    </div>
</body>

</html>
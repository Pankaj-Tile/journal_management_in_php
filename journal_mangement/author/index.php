<?php
session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'author') {
    header('Location: ../login.php');
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();
// Fetch manuscripts in descending order of their submission
$stmt = $pdo->prepare('SELECT * FROM manuscripts WHERE author_id = ? ORDER BY created_at DESC LIMIT 5');
$stmt->execute([$userId]);
$manuscripts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Author Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

       
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
		<link rel="stylesheet" href="../assets/css/bootsnav.css" >	
        
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
            float: left; /* Align image to the left */
            margin-right: 20px; /* Add some space between image and buttons */
        }
        .profile-actions {
            padding-top: 110px; /* Align buttons with the bottom of the image */
        }
        .profile-actions button {
            margin-right: 50px;
            display: block;
            width: fit-content;
            margin-bottom: 10px; /* Add some space between buttons */
        }
    </style>
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
<div class="container" id="dashboard">
    <div class="row">
        <div class="col-md-2 text-center">
            <img src="../uploads/profile_images/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="profile-image">
            <div class="profile-actions">
            <p><a href="../edit_profile.php"><?php echo htmlspecialchars($user['username']); ?></a></p>
                <a href=""><form method="post">
                    <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                </form></a>
                <a href="submit.php"><button class="btn btn-primary"> Post Journal</button></a>
            </div>
           
        </div>

        <!-- Start Header Navigation -->
        <div class="navbar-header">
			                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
			                    <i class="fa fa-bars"></i>
			                </button>
			                <a class="navbar-brand" href="index.html">Author Dashboard</a>
			            </div><!--/.navbar-header-->
			            <!-- End Header Navigation -->

			            <!-- Collect the nav links, forms, and other content for toggling -->
			            <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
			                <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
			                <li class="smooth-menu active"></li>
			                    <li class="smooth-menu"><a href="#about">Author Role</a></li>
			                    <li class="smooth-menu"><a href="#workflow">WorkFlow for author</a></li>
								<li class="smooth-menu"><a href="#submissions">Your Journals</a></li>
			                    <li class="smooth-menu"><a href="#contact">Contact</a></li>
			                    <li ><a href="../publish.php">Journals</a></li>

			                  
			                
			                </ul><!--/.nav -->
			            </div><!-- /.navbar-collapse -->
			        </div><!--/.container-->
			    </nav><!--/nav-->
			    <!-- End Navigation -->
			</div><!--/.header-area-->

     <div id="submissions">
	 <div class="section-heading text-center">
				<h2>Your Journals</h2>
			</div>
<table class="table" style="overflow-y: scroll; height: 200px;" >
    <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Submitted On</th>
            <th>Reviewer Comments</th> <!-- Corrected column for reviewer comments -->
            <th>Actions</th> <!-- Column for actions -->
        </tr>
    </thead>
    <tbody>
            <?php foreach ($manuscripts as $manuscript):
                // Query to get all the reviewer comments for the current manuscript
                $stmt = $pdo->prepare('SELECT comments FROM reviews WHERE manuscript_id = ? ORDER BY id DESC');
                $stmt->execute([$manuscript['id']]);
                $reviews = $stmt->fetchAll();
            ?>
            <tr>
                <td><?php echo htmlspecialchars($manuscript['title']); ?></td>
                <td><?php echo htmlspecialchars($manuscript['status']); ?></td>
                <td><?php echo htmlspecialchars($manuscript['created_at']); ?></td>
                <td>
                        <?php foreach ($reviews as $review): ?>
                            <div><?php echo htmlspecialchars($review['comments']); ?></div>
                        <?php endforeach; ?>
                    </td>
                <td>
                    <?php
                    // Provide a resubmit link if the manuscript status is 'revised'
                    if ($manuscript['status'] == 'revised') {
                        echo '<a href="resubmit.php?manuscript_id=' . $manuscript['id'] . '">Resubmit</a>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>
<section id="about" class="about">
			<div class="section-heading text-center">
				<h2>Author Role</h2>
			</div>
			<div class="container">
				<div class="about-content">
					<div class="row">
						<div class="col-sm-6">
							<div class="single-about-txt">
								<h3>
								Role Overview:
An author in a journal management system is responsible for submitting original research manuscripts for publication. Authors must ensure their work adheres to the journal's guidelines and standards.	</h3>
								<p>
								Key Responsibilities:

Manuscript Submission:

Authors submit their original research manuscripts through the online submission system.
They provide all necessary details, including the manuscript title, abstract, references, and the document itself (in PDF or DOC format).
Revision:

Based on feedback from reviewers, authors must revise their manuscripts to address any issues or suggestions.
Authors resubmit the revised manuscripts for further review.
Compliance:

Ensure that all submissions adhere to the journal's formatting and ethical guidelines.
Maintain accurate and complete records of all submissions and revisions.
Communication:

Stay in contact with the editorial team and reviewers throughout the review process.
Respond promptly to any queries or requests for additional information. </p>
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
								<img src="../assets/images/about/author.jpg" alt="profile_image">
								<div class="about-list-icon">
									<ul>
										<li>
											<a href="#">
												<i  class="fa fa-facebook" aria-hidden="true"></i>
											</a>
										</li><!-- / li -->
										<li>
											<a href="#">
												<i  class="fa fa-dribbble" aria-hidden="true"></i>
											</a>
											
										</li><!-- / li -->
										<li>
											<a href="#">
												<i  class="fa fa-twitter" aria-hidden="true"></i>
											</a>
											
										</li><!-- / li -->
										<li>
											<a href="#">
												<i  class="fa fa-linkedin" aria-hidden="true"></i>
											</a>
										</li><!-- / li -->
										<li>
											<a href="#">
												<i  class="fa fa-instagram" aria-hidden="true"></i>
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


		<!-- Controls -->
 <div id="workflow">
<div class="d-flex justify-content-center mb-4">
  <div class="section-heading text-center">
    <h2>Author WorkFlow And Functionality</h2>
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
            <img
              src="../assets/images/about/author-home.jpg"
              class="card-img-top"
             
            />
            <div class="card-body">
              <h5 class="card-title">
                This image shows the home page of a Author. <br> <br>
              </h5>
              <p class="card-text">
              Key elements include: <br>
              1. Profile Picture: A circular profile picture of the Author. <br>
              2.Email: User name of the author with edit profile functionality which provide form to edit username, email and profile picture <br>
              3.Logout Button: A red "Logout" button of exit form the session.  <br>
              4.Post Journal button: This button redirect to the post journal form. On that page is submitted.<br>
              </p>
            </div>
          </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
          <div class="card">
            <img
              src="../assets/images/about/author-nav.jpg"
              class="card-img-top"
            
            />
            <div class="card-body">
              <h5 class="card-title">
                This image displays the top navigation menu for a author. <br> <br>
              </h5>
              <p class="card-text">
                1. Author Role: It defines the actions of Author. <br>
                2. WorkFlow for author: This define the workflow for author. <br>
                3. Your Journals : This shows the Journals submitted by the author. <br> 
                4. Contact : This is contact form by which reviwer can send message to admin <br>
                5. Journals : This is the page where all the published journal are displayed. <br>
              </p>
            </div>
          </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
          <div class="card">
            <img
              src="../assets/images/about/journal-status.jpg"
              class="card-img-top"
         
            />
            <div class="card-body">
              <h5 class="card-title">
                This image displays a section of a table listing journal
                manuscripts which are submitted by author. <br> <br>
              </h5>
              <p class="card-text">
                The columns include: <br />
                Title: Where name of journal is define <br />
                Status: where status about journal is define <br>
                
                Submitted: Journal is submitted by the user. <br />
                In Review: If reviewer is reviewing journal set this status with
                comments <br />
                Revised: If reviewer want any change select this status and
                mention in comments what change must be done. <br />
                Accepted: If journal is acceptable with no further correction
                then set this status <br />
                Rejected: If author find <br />
                View Document: A link to "View Document" for accessing the
                manuscript. <br />
                Action: If resubmit status is set then auther can re-submit the manuscript of jornal here along with appropriate changes . <br>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

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
    </div>
</div>
</body>
</html>


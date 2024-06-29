<?php
session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();
$sqlfeed = "SELECT * FROM contacts ORDER BY submitted_at DESC LIMIT 5";
$stmt = $pdo->query($sqlfeed);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
        
    </style>
</head>
<body>
<div class="container">
<div class="row">
        <div class="col-md-2 text-center">
            <img src="../uploads/profile_images/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="profile-image">
            
            <div class="profile-actions">
            <p><a href="../edit_profile.php"><?php echo htmlspecialchars($user['username']); ?></a></p>
                <a href=""><form method="post">
                    <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                </form></a>
                <a href="manage_journals.php"><button class="btn btn-primary"> Publish Journal</button></a>
            </div>
           
        </div>

     <!-- Start Header Navigation -->
     <div class="navbar-header">
			                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
			                    <i class="fa fa-bars"></i>
			                </button>
			                <a class="navbar-brand" href="#">Admin Dashboard</a>
			            </div><!--/.navbar-header-->
			            <!-- End Header Navigation -->

			            <!-- Collect the nav links, forms, and other content for toggling -->
			            <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
			                <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
			                <li class="smooth-menu active"></li>
			                    <li class="smooth-menu"><a href="#about">Admin Role</a></li>
			                    <li class="smooth-menu"><a href="#workflow">WorkFlow for Admin</a></li>
                                <li ><a href="reviewer_registration.php">Reviewer Registration</a></li>
                                <li class="smooth-menu"><a href="#feedback">feedback</a></li>
			                   
                                
			                    <li ><a href="../publish.php">Journals</a></li>

			                  
			                
			                </ul><!--/.nav -->
			            </div><!-- /.navbar-collapse -->
			        </div><!--/.container-->
			    </nav><!--/nav-->
			    <!-- End Navigation -->
			</div><!--/.header-area-->

            
 
    

    <div id="feedback" >
    <div class="section-heading text-center">
				<h2>Feedbacks</h2>
			</div>
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
            </tr>
            <?php while ($row = $stmt->fetch()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>


    <section id="about" class="about">
			<div class="section-heading text-center">
				<h2>Admin Role</h2>
			</div>
			<div class="container">
				<div class="about-content">
					<div class="row">
						<div class="col-sm-6">
							<div class="single-about-txt">
								<h3>
								Admins in a journal management system are responsible for overseeing the entire editorial process, from manuscript submission to publication. They manage the workflow, coordinate with authors and reviewers, and ensure the smooth operation of the journal.</h3>
								<p>
                                Key Responsibilities:

Manuscript Management:

Admins oversee the submission and initial screening of manuscripts, ensuring they meet the journal's basic criteria before sending them for review.
Assign manuscripts to appropriate reviewers based on their expertise.
Coordination:

Serve as the primary point of contact between authors, reviewers, and the editorial board.
Ensure clear and timely communication throughout the review and publication process.
Decision Making:

Make final decisions on the acceptance, revision, or rejection of manuscripts based on reviewers' recommendations.
Ensure that accepted manuscripts are properly formatted, proofread, and prepared for publication.
User Management:

Manage user accounts, including registration, role assignment, and profile updates.
Maintain the integrity and security of the journal management system.
Publication:

Oversee the publication of accepted manuscripts on the journal's website.
Ensure that published content is accessible to the public and adheres to the journal's standards.								</p>
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
								<img src="../assets/images/about/admin.jpg" alt="profile_image">
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

<div id="workflow">
    <!-- Controls -->
<div class="d-flex justify-content-center mb-4">
  <div class="section-heading text-center">
    <h2>Admin WorkFlow And Functionality</h2>
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
              src="../assets/images/about/admin-home.jpg"
              class="card-img-top"
             
            />
            <div class="card-body">
              <h5 class="card-title">
                This image shows the home page of a Admin. <br> <br>
              </h5>
              <p class="card-text">
                Key elements include:  <br>
                1.Profile Picture: A circular profile
                picture of the Admin.  <br>
                2.Email: User name of the admin with edit profile functionality which provide form to edit user name email and profile picture <br>
                3.Logout Button: A red "Logout" button of exit form the session.  <br>
                4.Manage journal button : This button redirect to to manage journal form where all the accepted journal is published or rejected <br>
            </div>
          </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
          <div class="card">
            <img
              src="../assets/images/about/admin-nav.jpg"
              class="card-img-top"
            
            />
            <div class="card-body">
              <h5 class="card-title">
                This image displays the top navigation menu for Admin. <br> <br>
              </h5>
              <p class="card-text">
                1. Author Role: It defines the actions of Admin. <br>
                2. WorkFlow for admin: This define the workflow for admin. <br>
                3. Reviewer Registration : This is section where admin can register reviewer. <br> 
                4. Feedback : This section shows all the message made by author,reviewer and admin.<br>
                5. Journals : This is the page where all the published journal are displayed. <br>
              </p>
            </div>
          </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
          <div class="card">
            <img
              src="../assets/images/about/admin-publish.jpg"
              class="card-img-top"
         
            />
            <div class="card-body">
              <h5 class="card-title">
                This image displays a section of a table listing journal
                manuscripts for Publication. <br> <br>
              </h5>
              <p class="card-text">
                The columns include: <br />
                Title: Where name of journal is define <br />
                Author: where name of journal author is define <br />
                Abstract: where abstract about journal is define <br />
                Status: where status about journal is define <br>
                Action: This form can update the status of journal to publish or reject if publish then it will be display to all else rejectd. <br>
                View Document: A link to "View Document" for accessing the
                manuscript. <br />
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- end of wrapper -->
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

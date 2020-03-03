<?php
	require '/var/www/html/aws/aws-autoloader.php';
	require 's3key.php';

	use Aws\S3\S3Client;

	use Aws\Exception\AwsException;

	session_start();

	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: login.php");
	}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header1">
		<h2>Welcome <strong><?php echo $_SESSION['username']; ?></strong></h2>

	</div>

	<div class="content">

		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php
						echo $_SESSION['success'];
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>

		<!-- logged in user information -->
		<?php  if (isset($_SESSION['username'])) : ?>
		<div align="right"><h4><a href="index.php?logout='1'" style="color: red;">logout</a> </h4></div>

		<br><br>

    	<?php
			//include the S3 class
			error_reporting(0);
			if (!class_exists('S3'))require_once('S3.php');

			//AWS access info
			if (!defined('awsAccessKey')) define('awsAccessKey', 'CHANGE THIS');
			if (!defined('awsSecretKey')) define('awsSecretKey', 'CHANGE THIS TOO');

			//instantiate the class

			$s3 = new S3(XXXXXXXXXXXXXXXXXXXX, XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX);

			//check whether a form was submitted
			if(isset($_POST['Submit'])){

				//retreive post variables
				$fileName = $_FILES['theFile']['name'];
				$fileTempName = $_FILES['theFile']['tmp_name'];

				//create a new bucket
				//$s3->putBucket("trainingapp0303", S3::ACL_PUBLIC_READ);

				$fileNameb=$_SESSION['username']."/".$fileName;
				echo $fileName;
				echo $fileNameb;
				//move the file
				if ($s3->putObjectFile($fileTempName, "trainingapp0303", $fileNameb, S3::ACL_PUBLIC_READ)) {
					echo "<strong>We successfully uploaded your file.</strong>";
				}else{
					echo "<strong>Something went wrong while uploading your file... sorry.</strong>";
				}
			}
		?>
<h3>Select and Upload a file</h3> <br>

   	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input name="theFile" type="file" />
	  <button class="button button4">
      <input name="Submit" type="submit" value="Upload"></button>
	</form>
	<br><br>
<h2>List of all uploaded files</h3>
<table>

<?php
	// Get the contents of our bucket


		try {
		$objects = $s3Client->getIterator('ListObjects', array(
			'Bucket' => 'trainingapp0303',
			'Prefix' => $_SESSION['username'].'/'
		));
		$key1= 'Perld.txt';
			foreach ($objects as $object) {
				?><tr><td> <?php
				echo $object['Key'] . "\n";
				$keyy='/'.$object['Key'];
				?>

				<form action="" method="post">
				</td><td><button type="submit"  name="login_user">Download</button><br> </td>
				</form>
				</tr>

				<?php

				if(isset($_POST['login_user'])){
					 //code to be executed
					 try {
						$result = $s3Client->getObject(array(
						'Bucket' => 'trainingapp0303',
						'Key' => $keyy,
						'SaveAs' => $key1
						));
					}
					catch (S3Exception $e) {
			             // output error message if fails
			              echo $e->getMessage();
                          }

				}else{
					 //code to be executed
				}
			} ?> </table> <?php
		} catch (S3Exception $e) {
			echo $e->getMessage() . "\n";
		}





?>







		<?php endif ?>
	</div>

</body>
</html>

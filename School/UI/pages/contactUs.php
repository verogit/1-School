<?php 

$page_title = 'Contact Us';
include ("../includes/header.php");

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array(); // Initialize an error array.
	
	// Check for a  name:
	if (empty($_POST['name'])) {
		$errors[] = 'You forgot to enter your name.';
	}
		// Check for a phone_number:
	if (empty($_POST['phone'])) {
		$errors[] = 'You forgot to enter your phone number.';
	}
	
	// Check for an email:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email.';
	}
	// Check for an message:
	if (empty($_POST['message'])) {
		$errors[] = 'You have to enter your message.';
	}
	
	if (empty($errors)) { // If everything's OK.
	    echo '<h1>Thank you for writing to us!</h1>
			<p>We will contact you soon. </p><p><br /></p>';	
	} else { // Report the errors.
		
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

} // End of the main Submit conditional.
?>
<form action="" method="post">
	Name:<input class="info" type="text" name="name" size="15" maxlength="20" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /></td>
	Phone Number:<input class="info" type="text" name="phone" size="20" maxlength="60" value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>"  /></td>
	Email:<input class="info" type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /></td>
	Message:<textarea class="info" name="message" rows ="10"  value="<?php if (isset($_POST['message'])) echo $_POST['message']; ?>"></textarea></td>
	<input class="submit" type="submit" name="submit" value="Send" />
</form>
<?php include ('../includes/footer.php'); ?>

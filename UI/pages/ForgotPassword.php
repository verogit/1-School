<?php
    
    require_once('../../DAL/UserRepository.php');
    require_once('../../Domain/User.php');
    
    include ("../includes/header.php"); 

    error_reporting( error_reporting() & ~E_NOTICE );
    
    $userRepository = new UserRepository();
    
    
    if (isset($_POST['submit'])) 
    {
        if ( !isset($_POST['email'])) {
             $errors[] = "You must fill all the required fields";
        }
        else
        {
            $user = $userRepository->GetUserByEmail($_POST['email']);
            
            if($user != null)
            {
                $to = $_POST['email'];
                $subject = "Password Recovery";
                
                $messageEmail = "
                <html>
                    <head>
                        <title>School System Password Recovery!</title>
                    </head>
                    <body>
                        <p>This e-mail is in response to your recent request to recover a forgotten password. Password security features are in place to ensure the security of your profile information</p>
                        <table>
                            <tr>
                                <th>Username</th>
                                <th>Password</th>
                            </tr>
                            <tr>
                                <td>#username</td>
                                <td>#password</td>
                            </tr>
                        </table>
                    </body>
                </html>
                ";
                
                $messageEmail = str_replace("#username",$user->userName,$messageEmail);
                $messageEmail = str_replace("#password",$user->password,$messageEmail);
                
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                
                // More headers
                $headers .= 'From: <webmaster@school.com>' . "\r\n";
                
                mail($to,$subject,$messageEmail,$headers);
                
                $message = "The username and password have been sent to your email";
                unset($_POST);
            }
            else
            {
                $errors[] = "There is not a user registered with that email";
            }
            
        }
           
        
        
    }
            
    if(!empty($errors)){
		echo '<h1>Error!</h1>
		<p class="error">There are problems with this form. Please correct the following errors:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
	}	
            
    if(isset($message)){
   		echo "<span style='color:green;'>".$message."</span>";
    }
           
	?>
	
	<form action="" method="POST">
		<p> Email: <input type="email" placeholder="input your email" name="email" maxlength=100 required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"> </p>
		<button type="submit" name="submit" value="Send">Send</button>
	</form>
</body>
</html>



<?php 	include ("../includes/footer.php"); ?>
<?php
/* 
 * HybridAuth returns access tokens from Twitter, Google or Facebook. 
 * The application then checks if the authenticated user is authorized 
 * to enter the vehicle role-based page. This is checked in page.php.
 */
	session_start();
	include('library/config.php');
	include('library/Hybrid/Auth.php');
	include('page.php');
        
	if(isset($_GET['provider'])){
		$provider = $_GET['provider'];
		
		try{
			$hybridauth = new Hybrid_Auth( $config );   
			
			$authProvider = $hybridauth->authenticate($provider);

			$user_profile = $authProvider->getUserProfile();
						
						
			if($user_profile && isset($user_profile->identifier)){
				
				// call to page.php and check if the authenticated user is qualified
				$loginName = $user_profile->displayName;
				checkUser($loginName, $provider);    
			}
		}
		
		// catch error cases
		catch( Exception $e ){ 
			switch( $e->getCode() ){
				case 0 : echo "Unspecified error."; break;
				case 1 : echo "Hybridauth configuration error."; break;
				case 2 : echo "Provider not properly configured."; break;
				case 3 : echo "Unknown or disabled provider."; break;
				case 4 : echo "Missing provider application credentials."; break;
				case 5 : echo "Authentication failed. "
								 . "The user has canceled the authentication or the provider refused the connection.";
						 break;
				case 6 : 
					echo "You are not a registered authorized user. <br>Please contact the administrator ls223aa@student.lnu.se <br>";
				echo "<br> <a href='index.php'>Go to login start page</a>";
						break;
				case 7 : echo "User not connected to the provider.";
						 break;
				case 8 : echo "Provider does not support this feature."; break;
			}
		}
	}
?>
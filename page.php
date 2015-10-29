
<?php

/*
 * In checkUser(), the authenticated user is checked if it is registered in my database table Users. 
 * If so, the application starts generating the data in the role-based page.
 */
include('datas/dbDials.php');
include('datas/xmlData.php');


function checkUser($loginName, $provider){
    $userID = getUserID($loginName, $provider);
    
    //if the user is not an authorized member
    if($userID==null){
            echo "You are not a registered authorized user. <br>Please contact the administrator ls223aa@student.lnu.se <br>";
            echo "<br> <a href='index.php'>Go to login start page</a>";
    }
    
    //login ok, the role-based page can be generated
    else{
        generatePage($userID);
    }
}

//generate the role-based website
function generatePage($userID){
    
    //user info
	echo "<h2>User info</h2>";
    $user = new User($userID);
    $orgID = $user->orgID;
    $roleID = $user->roleID;
    
    echo "User name: ".$user->userName."<br>";
    echo "User ID: ".$userID."<br>";
    echo "email: ".$user->email. "<br>";
    echo "Organization ID: ".$orgID. "<br>";
    echo "Role ID: ".$roleID. "<br>";
    
    //role based page
    switch ($roleID) {
        case 2:
            driverPage($orgID);
            break;
        case 5:
			header("location: analyst/analyst.php?u=".$userID."&". htmlspecialchars(SID));
			exit();

            break;
        case 9:
			header("location: director/director.php?u=".$userID ."&". htmlspecialchars(SID));
			exit();
            break;
        default:
            echo "<br> (this user has role page) <br>";
}    
    
    //logout link
    echo "<br><br><br> <a href='logout.php'><button style='font-size:16px'>Logout</button></a>";
}

?>
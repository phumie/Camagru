<?php 
    session_start();
    include_once "pdo.php";

    if (!empty($_SESSION['username'])) 
    {
        try
        {
            $username = $_SESSION["username"];
            
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $name = $row['name'];
                $surname = $row['surname'];
                $email = $row['email'];
                $password = $row['password'];
                $notif = $row['notif'];
            }

            if (isset($_POST['enter']))
            {
                $flag = 0;
                if (!empty($_POST['name']))
                {
                    $sql = "UPDATE users SET name = :name WHERE username = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":name", $_POST['name']);
                    $stmt->bindParam(":username", $username);
                    $stmt->execute();
                    $flag = 1;
                }
                if (!empty($_POST['surname']))
                {
                    $sql = "UPDATE users SET surname = :surname WHERE username = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":surname", $_POST['surname']);
                    $stmt->bindParam(":username", $username);
                    $stmt->execute();
                    $flag = 1;
                }
                if (!empty($_POST['email']))
                {
                    $sql = "UPDATE users SET email = :email WHERE username = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":email", $_POST['email']);
                    $stmt->bindParam(":username", $username);
                    $stmt->execute();
                    $flag = 1;
                }
                if (!empty($_POST['oldpass']))
                {
                    if (password_verify($_POST['oldpass'], $password))
                    {
                        if ( strcmp($_POST['newpass'], $_POST['re-newpass']) == 0)
                        {
                            $newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
                            $sql = "UPDATE users SET password=:newpass WHERE username = :username";
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(":username",$username);
                            $stmt->bindParam(":newpass",$newpass);
                            $stmt->execute();  
                            $flag = 1;                   
                        }
                        else
                        {
                            echo "<script>alert('New password and re-password do not match')</script>";  
                        }
                    }
                    else
                    {
                        echo "<script>alert('Old password does not match')</script>";  
                    }
                }
                if (isset($_POST['notif']))
                {
                    $notif = 1;
                    $sql = "UPDATE users SET notif=:notif WHERE username = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":username",$username);
                    $stmt->bindParam(":notif",$notif);
                    $stmt->execute();  
                    $flag = 1;                   
                }
                if ($flag == 1)
                {
                    echo "<script>alert('Your USER info has been updated')</script>"; 
                    $flag = 0;
                }           
            }       
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
        
    }
    else
    {
        echo "<script>alert('You are not logged in.');</script>";
        ?>
        <html><meta http-equiv="refresh" content="1; url=../index.php"></html>
            <?php
        exit();
    }

    

    
?>


<html>
    <head>
        <title>Profile Settings</title>
        <link href="../css/home.css" rel="stylesheet" type="text/css">
        <link href="../css/settings.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div class="topnav">
        <a class="left" href="gallery.php"><img class="img" src="http://www.iconsdb.com/icons/preview/white/home-7-xxl.png" alt="HOME"></a>
        <a class="active" href="../server/logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
        <a href="../server/settings.php"><img class="img" src="http://flaticons.net/icons/Mobile%20Application/Settings-01.png" alt="SETTINGS"></a>
        <!-- <a href="profile.php"><img class="img" src="http://flaticons.net/icons/Application/User-Profile.png" alt="PROFILE"></a> -->
        <a href="camera.php"><img class="img" src="http://www.waltersgrovebaptistchurch.com/camera_01.png" "TAKEPHOTO"></a>
    </div> 

    <div class="footer">
            <p>&copy Phumie Nevhutala 2017</p>  
    </div>
    
    <div class="tandc">
    <div class="tandc2">
        <h2 align="center">Welcome to Camagru</h2>
        <h3 align="center">Here is a Summary of Camagru’s Terms of Use:</h3>
            <p>
                <ul>
                <li>Camagru can only be used in the South Africa, France, and any country with a 42 school.</li><br />
                <li>You are solely responsible for protecting your own account password and other account information.</li><br />
                <li>Unless you have an active Business Camagru account, Pandora is for personal use only. That means if you decide to market or sell on Camagru, Camagru cannot be held reliable for any business misconduct.</li><br />
                <li>You can’t use Camagru to steal photographs or other content. If you do, you will be suspended from using Camagru and further action will be taken against you.</li><br />
                <li>Do not use Camagru if you do not agree to the Terms of Use described below. Your use of Camagru means you agree to these Terms of Use.</li><br /></p>
            </ul>
    </div></div>
    <div class="settings">
        <h2>EDIT PROFILE INFO</h2>
        <form action="settings.php" method="post">
            <label>Name </label><input id="name" name="name" type="text" placeholder="<?php echo $name?>"><br>
            <label>Surname </label><input id="surname" name="surname" type="text" placeholder="<?php echo $surname?>"><br>
            <label>Username </label><input id="username" name="username" type="text" placeholder="<?php echo $username?>" readonly><br>
            <label>Email </label><input id="email" name="email" type="email" placeholder="<?php echo $email?>"><br>
            <br>
            <br>
            CHANGE PASSWORD <br>
            <br>
            <label>Old Password </label><input id="oldpass" name="oldpass" type="password"><br>
            <label>New Password </label><input id="newpass" name="newpass" type="password"><br>
            <label>Re-Password </label><input id="re-newpass" name="re-newpass" type="password"><br>
            <br>
            OTHER OPTIONS <br>
            <br>
            <input type="radio" name="notif" id="notif"> Recieve notifications when a user likes or comments on my post. <br>
            <br>
            <center><input type="submit" name="enter" id="submit" value="SUBMIT"></center>
        </form>
    </div>
    </body>
</html>
<?php
    session_start();
    include ('pdo.php');
        
        try
        {
            if (isset($_POST['submit']) && !empty($_POST['email']))
            {
                $email = $_POST['email'];
                $query = "SELECT * FROM users WHERE email = :email";

                $stmt = $db->prepare($query);
                $stmt->execute(array('email' => $email));
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                   $mail = $row['email'];
                   $_SESSION['pin'] = $row['verify_pin'];
                }
               if (strcmp($email, $mail) == 0)
                 {
                        $pin = rand(1000, 10000);
                        echo $_SERVER['PHP_SELF'];
                       $_SESSION['pin'] = $pin;
                        $sql = "UPDATE users SET verify_pin=:pin WHERE email=:email";
                       $stmt = $db->prepare($sql);
                       $stmt->bindParam(":pin",$pin);
                       $stmt->bindParam(":email",$email);
                        $stmt->execute();
                        
                        
                        echo "<script type='text/javascript'>alert('An email has been sent to you to verify your new changes. Please check to activate.');</script>";
                        $to=$mail;
                        $msg= "Password Reset for CAMAGRU";
                        $subject="Password Reset";
                        $headers = "MIME-Version: 1.0"."\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                        $headers .= 'From:Camagru Admin <Admin@admin.com>'."\r\n";
                        $ms ="<html></body><div><div>Dear Greetings,</div></br></br>";
                        $ms.="<div style='padding-top:8px;'>Your account information is successfully updated in our server. Please click the following link and enter the pin ($pin) to verify and activate the changes made to your account.</div>
                            <div style='padding-top:10px;'><a href='http://localhost:8080/camagru/server/forgotpass.php'>Click Here</a></div>
                            </div>
                            </body></html>";
                        mail($to,$subject,$ms,$headers);
                }  
                else
                {
                    echo "<script>alert('Your email does not exist in our database')</script>";
                }
            }
        }
        catch (PDOException $e) 
        {
            echo $e->getMessage().'<br>';
        }
?>

<html>
<head>
    <title>Forgot Password</title>
    <link href="../css/forgotpass.css" rel="stylesheet" type="text/css">
    <link href="../css/fonts.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Zeyada" />
</head>
<body>
    <center><div class="img">  
        <div class="centered">
        <div class="centered2">
            <br>
            <br>
            <img src="../images/forgot.png" alt="" align="center">
            <br>
            <br>
            <br>
            <br>
            <form style="font-family: Arial, Helvetica, sans-serif;" action="forgotpass2.php" method="POST">
            ENTER EMAIL <br>
            <input id="email" name="email" type="email" required><br>
            <input class="submit" type="submit" name="submit" value="SUBMIT">          
     </form>
    </div>
    </div>
    </div></center>
    <div class="signup"><p style="color: #0093b8;">NOT A MEMBER? <a href="../user/signup.php">SIGN UP.</a> </p></div>
    <div class="login"><p style="color: #0093b8;">YOU A MEMBER? <a href="../index.php">LOGIN.</a> </p></div>
</body>
</html>
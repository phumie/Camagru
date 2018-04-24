<?php
    session_start();
    include ('pdo.php');
    
    if (isset($_POST['submit']))
    {
        try
        {
            $pin = $_SESSION['pin'];
            $query = "SELECT user_id, verify_pin, password FROM users WHERE verify_pin = :pin";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":pin",$pin);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $user_id = $row['user_id'];
                $verify_pin = $row['verify_pin'];
                $oldpass = $row['password'];
            }

            if (strcmp($_POST['epass'], $verify_pin) == 0)
            {
                $password = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
                if (password_verify($_POST['newpass'], $oldpass))
                {
                    echo "<script>alert('Your Password Matches Your Old Password. Please Enter a Different Password.')</script>";
                }
                else
                {
                    if (isset($user_id))
                    {
                        
    
                        if (password_verify($_POST['repass'], $password))
                        {
                            $sql = "UPDATE users SET password=:password WHERE user_id=:user_id";
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(":user_id",$user_id);
                            $stmt->bindParam(":password",$password);
                            $stmt->execute();
                            header("location: ../index.php");
                        }
                    }
                }
            }
                
            else
            {
                echo "<script>alert('Emailed Pin Incorrect')</script>";
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
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
            <form style="font-family: Arial, Helvetica, sans-serif;" action="forgotpass.php" method="POST">
            EMAILED PIN <br>
            <input id="epass" name="epass" type="text" required><br>
            NEW PASSWORD <br>
            <input id="newpass" name="newpass" type="password" required><br>
            RE-PASSWORD <br>
            <input id="repass" name="repass" type="password" required><br>
            <input class="submit" type="submit" name="submit" value="SUBMIT">
            
     </form>
    </div>
    </div>
    </div></center>
    <div class="signup"><p style="color: #0093b8;">NOT A MEMBER? <a href="../user/signup.php">SIGN UP.</a> </p></div>
    <div class="login"><p style="color: #0093b8;">YOU A MEMBER? <a href="../index.php">LOGIN.</a> </p></div>
</body>
</html>
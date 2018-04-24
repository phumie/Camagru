<?php
    session_start();
    
        try
        {
            if (isset($_POST['submit']))
            {
                if (empty($_POST['username']) || empty($_POST['password']))
                {
                    echo "<script type='text/javascript'>alert('Username or Password cannot be empty.');</script>";
                }
                else
                {
                    // $db = new PDO('mysql:host=localhost;dbname=camagru', 'root', '123abc');
                    $db = new PDO('mysql:host=localhost;dbname=camagru', 'root', '');
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $username = $_POST['username'];
                    $password = $_POST["password"];
    
                    $query ="SELECT * FROM users WHERE username = :username";
                    $stmt = $db->prepare($query);
                    $stmt->execute(array(':username' => $username));
                    
                    while ($row = $stmt->fetch())
                    {
                        $user = $row['username'];
                        $hash_pass = $row['password'];
                        $reg_verify = $row['reg_verify'];
                        if ($reg_verify == 1) {
                            if (password_verify($password, $hash_pass))
                            {
                                $_SESSION["username"] = $user;
                                $_SESSION["password"] = $hash_pass;
                                header("location: user/welcome.php");
                            }
                            else
                            {
                                echo "<script type='text/javascript'>alert('Please enter correct username or password.');</script>";
                            }
                        }
                        else
                        echo "<script type='text/javascript'>alert('Please verify account before logging in.');</script>";
                        
                    }
                }
            }
        }
        catch (PDOException $e) 
        {
            echo $database.'<br>'.$e->getMessage();
        }
?>
<html>
<head>
    <title>Login Page</title>
    <link href="css/login.css" rel="stylesheet" type="text/css">
    <link href="css/fonts.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Zeyada" />
</head>
<body>
    <center><div class="img">  
        <div class="centered">
        <div class="centered2">
            <br>
            <br>
            <img src="images/login.png" alt="" align="center">
            <form action="index.php" method="POST">
                USERNAME <br>
                <input id="username" name="username" type="text" required><br>
                PASSWORD <br>
                <input id="password" name="password" type="password" required><br>
                <input class="submit" type="submit" name="submit" value="SUBMIT">
                
         </form>
    </div>
    </div>
    </div></center>
    <div class="signup"><p style="color: #0093b8;">NOT A MEMBER? <a href="user/signup.php">SIGN UP.</a> </p></div>
    <div class="forgot"><a href="server/forgotpass2.php">FORGOT PASSWORD</a></div>
</body>
</html>
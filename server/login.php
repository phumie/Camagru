<?php
    session_start();

    try
    {
        if (isset($_POST['submit']))
        {
            if (empty($_POST['username']) || empty($_POST['password']))
            {
                echo "<script type='text/javascript'>alert('Username or Password empty or invalid');</script>";
            }
            else
            {$db = new PDO('mysql:host=localhost;dbname=camagru', 'root', '');
                // $db = new PDO('mysql:host=localhost;dbname=camagru', 'root', '123abc');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $username = $_POST['username'];
                $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

                $query ="SELECT * FROM users WHERE username = :username AND password = :password";
                $stmt = $db->prepare($query);

                $stmt->execute
                (
                    array(
                        'username' => $_POST["username"],
                        'password' => passowrd_hash($_POST['password'])
                    )
                );
                while ($row = $stmt->fetch())
                {
                    $user = $row['username'];
                    $hash_pass = $row['password'];
                    $nam = $row['name'];
                    $sur = $row['surname'];
                    $mail = $row['email'];
                    if (password_verify($password, $hashed_pass))
                    {
                        $_SESSION["username"] = $user;
                        $_SESSION["password"] = $hash_pass;
                        $_SESSION["email"] = $mail;
                        $_SESSION["name"] = $nam;
                        $_SESSION["surname"] = $sur;
                        header("location: home.php");
                    }
                    else
                    {
                        echo "<script type='text/javascript'>alert('Please verify data');</script>";
                    }
                }
            }
        }
    }
    catch (PDOException $e) 
    {
        echo $database.'<br>'.$e->getMessage();
    }
?>
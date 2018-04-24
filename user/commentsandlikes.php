<?php    
    require_once "../server/pdo.php";
    session_start();
    try
    {
        // LIKE POST
        if (isset($_POST['like']))
        {   
            $value = $_POST['like'];
            $user = $_SESSION['username'];
            $sqlInsert = 'SELECT username FROM likes WHERE image = :image';
            $stmt = $db->prepare($sqlInsert);
            $stmt->bindParam(":image", $value);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $bool = 0;
            foreach ($row as $results)
                if ($results['username']  == $user)
                    $bool = 1;
            if ($bool == 0)
            {
                $sqlInsert = "INSERT INTO likes (username, image) VALUES (:username, :image)" ;
                $stmt = $db->prepare($sqlInsert);
                $stmt->bindParam(":username", $user);
                $stmt->bindParam(":image", $value);
                $stmt->execute();


                $sql = "UPDATE likescount SET count = count + 1 WHERE image = :image";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":image", $value);
                $stmt->execute();

        
            }
            else
            {
                $del = 'DELETE FROM likes WHERE username = :username AND image = :image';
                $stmt = $db->prepare($del);
                $stmt->bindParam(":username", $user);
                $stmt->bindParam(":image", $value);
                $stmt->execute();


                $sql = "UPDATE likescount SET count = count - 1 WHERE image = :image";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":image", $value);
                $stmt->execute();
            }
        }


        // COMMENT ON POST
        if (isset($_POST['com']))
        {
            $comment = $_POST['comment'];
            if (!empty($comment))
            {
                $value = $_POST['com'];
                $username = $_SESSION['username'];
                $sql = "INSERT INTO comments (image, username, comment) VALUES ( :image, :username, :comment)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":image", $value);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":comment", $comment);
                $stmt->execute();


                // SELECT USER TO SEND EMAIL TO
                $sql = "SELECT * FROM images";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":image", $value);
                $stmt->execute();
                $mailrow = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                foreach ($mailrow as $results => $image)
                {
                    if ($image['image'] == $value)
                    {
                        $to = $image['username'];
                    }
                }

                // SEND MAIL TO USER
                $sql = "SELECT notif FROM users WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":username", $to);
                $stmt->execute();
    
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    if ($row['notif'] == 1 && !empty($comment))
                    {
                        $msg= "New Comment from $username";  
                        $subject="Email Verification";
                        $headers = "MIME-Version: 1.0"."\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                        $headers .= 'From:Camagru Admin <phumie.nevhutala@gmail.com>'."\r\n";
                        $ms ="<html></body><div><div>Hello $to!</div></br></br>";
                        $ms.="<div style='padding-top:8px;'>$username posted a new comment on your cama post.</div>
                            <div style='padding-top:10px;'>'$comment'</div>
                            </div>
                            </body></html>";
                        mail($to,$subject,$ms,$headers);
                       $result = "<p style='padding: 20px; color: green;'> Check your profile for more comments. </p>";
                    }
                    else
                        break;
                }
            }
            else
                echo "<script>alert('Comment empty. Add text before commenting.')</script>";

            echo "<meta http-equiv='refresh' content='0'>";
        }


        // DELETE POST
        if (isset($_POST['delpost']))
        {
            $delimg = $_POST['delpost'];
            $imgdir = "../images/uploads/";
            
            $sql = "DELETE FROM images WHERE image = :image";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":image", $delimg);
            $stmt->execute();
            
            unlink($imgdir.$delimg);
        }
    }
    catch (PDOException $e) 
    {
        echo '<br>'.$e->getMessage();
    }
?>
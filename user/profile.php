<?php
    require_once "../server/pdo.php";
   session_start();
?>
<html>
    <head>
        <title>Home</title>
        <link href="../css/home.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="topnav">
            <a class="left" href="home.php"><img class="img" src="http://www.iconsdb.com/icons/preview/white/home-7-xxl.png" alt="HOME"></a>
            <a class="active" href="../server/logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
            <a href="../server/settings.php"><img class="img" src="http://flaticons.net/icons/Mobile%20Application/Settings-01.png" alt="SETTINGS"></a>
            <a href="profile.php"><img class="img" src="http://flaticons.net/icons/Application/User-Profile.png" alt="PROFILE"></a>
            <a href="camera.php"><img class="img" src="http://www.waltersgrovebaptistchurch.com/camera_01.png" "TAKEPHOTO"></a>
        </div>

    <?php
    try
    {
        $files = glob("../images/uploads/*.*");

        for ($i=0; $i<count($files); $i++)
        {
            $imagepath = $files[$i];
            $imagename = $filename = substr(strrchr($imagepath, "/"), 1);
            $sql = "SELECT username FROM images WHERE image = :imagename";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":imagename",$imagename);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $username = $row['username'];
            }
    ?>
        <div class="galbox">
            <div class = "gallery">
                <p><?php echo strtoupper($username)?></p>
                <img class = "gallery" src="<?php echo $imagepath ?>" alt="Random image" /> <br>
                <!-- <textarea rows="3" cols="30">
                
                </textarea> -->
                <button><img class="button" src="http://www.freeiconspng.com/uploads/youtube-like-png-14.png" alt=""></button>
                <input class="comment" type="text" name="comment" id="comment" placeholder="comment">
            </div>
        </div>
        
    <?php
        }
    }
    catch (PDOException $e) 
    {
        echo $database.'<br>'.$e->getMessage();
    }
    ?>
    </body>
</html>
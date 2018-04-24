<?php
    
    include_once "commentsandlikes.php";
    include_once "../config/database.php";

    try{
        $sql = "SELECT * from comments";
        $stmt = $db->prepare($sql);
        $stmt->execute();  
        $comrow = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        $sql  = "SELECT * FROM likes";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $likesrow = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $likesnum = 0;

        /**************** PAGINATION ****************/
        
        //check the page I'm on
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $perPage = 6;

        //point in which the images are going to start
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
    }
    catch (PDOException $e) {
        echo '<br>'.$e->getMessage();
    } 

    


?>


<html>
    <head>
        <title>Home</title>
        <link href="../css/home.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div class="topnav">
        <a class="left" href="gallery.php"><img class="img" src="http://www.iconsdb.com/icons/preview/white/home-7-xxl.png" alt="HOME"></a>
        <a class="active" href="../server/logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
        <a href="../server/settings.php"><img class="img" src="http://flaticons.net/icons/Mobile%20Application/Settings-01.png" alt="SETTINGS"></a>
        <a href="camera.php"><img class="img" src="http://www.waltersgrovebaptistchurch.com/camera_01.png" "TAKEPHOTO"></a>
    </div>

    <h1><?php if (empty($_SESSION['username'])) echo "Welcome to Camagru. To like and comment please login.";?></h1>

    <div class="footer">
            <p>&copy Phumie Nevhutala 2017</p>  
    </div>
    
    <?php

    try
    {
        $limit = 8;
        $imagepath = "../images/uploads/";
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM images LEFT JOIN likescount ON images.image = likescount.image LIMIT $start, $perPage";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $items = $db->query("SELECT FOUND_ROWS() as total")->fetch(PDO::FETCH_ASSOC)['total'];
        $pages = ceil($items / $perPage);

    ?>
        <p align="center"><<<?php for ($x = 1; $x <= $pages; $x++): ?>
        <a href="?page=<?php echo $x;?>"><?php echo $x;?></a>
        <?php endfor;?>>></p>
    <?php

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $username = $row['username'];
            $image = $row['image'];
            $count = $row['count'];

    ?>
    <div class="galbox">
        <div class="gallery">
            <form action="" method="post">
                <p class="username"><?php echo strtoupper($username)?></p>
                <img class="gallery" src="<?php echo $imagepath.$image?>" alt="">
                <button class="com" name="delpost" id="delpost" value="<?php echo $image?>" <?php if (empty($_SESSION['username'])) echo "disabled"; else{if ($username != $_SESSION['username'])echo "disabled";}?>><img src="https://cdn2.iconfinder.com/data/icons/metro-uinvert-dock/128/Recycle_Bin_Full.png" height="25" width="25"></button>
                        <script>
                            function disablebtn(username){
                                user = <?php $_SESSION['username'] ?>;
                                if (username != user)
                                    document.getElementById("delpost").disabled = true; 
                                else
                                document.getElementById("delpost").disabled = false; 
                            }

}
                        </script>
                <button class="com" name="com" id="com" value="<?php echo $image?>" <?php if (empty($_SESSION['username'])) echo "disabled";?>><img src="../images/comment.png" height="25" width="25"></button>
                <button class="com" name="like" id="like" value="<?php echo $image?>" <?php if (empty($_SESSION['username'])) echo "disabled";?>><img src="http://www.iconarchive.com/download/i66645/designbolts/free-valentine-heart/Heart.ico" height="25" width="25"></button>
                <label><?php echo $count." likes"; ?></label>        
                <input class="comment" type="text" name="comment" id="comment" placeholder="comment"><br><br>
                <div class="combox">
                    <p><?php
                        foreach ($comrow as $results => $value)
                        {
                            if ($value['image'] == $image)
                            {
                                $user = $value['username'];
                                $comment = $value['comment'];
                                echo strtoupper($user)." said: <br>".$comment."<br>";
                            }
                        }
                    ?></p>
                </div>
                
                
            </form>
        </div>
    </div>
    <?php
        } 
    }
    catch (PDOException $e) 
    {
        echo '<br>'.$e->getMessage();
    }           
                
    ?>
    </body>
</html>
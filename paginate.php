<?php
    include_once "server/pdo.php";
    include_once "config/database.php";

    try
    {
        if (isset($_GET['page']))
            $page = (int)$_GET['page'];
        else
            $page = 1;

        $perPage = 3; //number of items per page

        //start from a certain image according to the page number.
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;



        $query = "SELECT SQL_CALC_FOUND_ROWS image FROM images LIMIT $start, $perPage";
        $stmt = $db->prepare($query);
        $stmt->execute();
    
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Number of pages
        $items = $db->query("SELECT FOUND_ROWS() as total")->fetch(PDO::FETCH_ASSOC)['total'];
        echo $pages = ceil($items / $perPage);
    }
    catch (PDOException $e) 
    {
        echo '<br>'.$e->getMessage();
    }
    
?>

<html>
    <head>
        <title>Paginate</title>
    </head>

    <body>
        <?php foreach ($images as $image): ?>
            <div class="gallery">
                <p><?php echo $image['image']; ?></p>
            </div>
        <?php endforeach;?>
        <div class="galbox">
            <?php for ($x = 1; $x <= $pages; $x++): ?>
            <a href="?page=<?php echo $x;?>"><?php echo $x;?></a>
        </div>
            <?php endfor;?>
    </body>
</html>



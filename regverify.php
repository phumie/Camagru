<?php
    include_once "server/pdo.php";

    try{

        if (isset($_GET['token']) && isset($_GET['user'])){
            $username = $_GET['user'];
        }

        $query ="UPDATE users SET reg_verify = 1 WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

    }
    catch (PDOException $e){
        $e->getMessage();
    }
?>

<html>
    <head>
        <title>User Verify</title>
        <link href="css/welcome.css" rel="stylesheet" type="text/css">
        <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Zeyada' type='text/css'/>
        <meta http-equiv="refresh" content="4; url=index.php">
    </head>
    <body>
        <h1>Account verified. Redirecting to login page.</h1>
        <img src="https://loading.io/spinners/pies/lg.pie-chart-loading-gif.gif">
    </body>
</html>
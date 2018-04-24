<?php
try
{
    $db = new PDO('mysql:host=localhost;dbname=camagru', 'root', '');
    // $db = new PDO('mysql:host=localhost;dbname=camagru', 'root', '123abc');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}
catch (PDOException $e)
{
    echo $e->getMessage();
}

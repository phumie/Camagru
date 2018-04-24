<?php
include_once 'database.php';
try {
	$dbname = "camagru";
	$DB = explode(';', $DB_DSN);
	$database = $dbname;
	$db = new PDO("$DB[0]", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("CREATE DATABASE IF NOT EXISTS $database");

	echo "Database '$database' created successfully.<br>";

	$db->exec("use $database");
	$db->exec("CREATE TABLE IF NOT EXISTS users (user_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(50) NOT NULL,
        email VARCHAR(255) NOT NULL,
		name VARCHAR(25) NOT NULL,
		surname VARCHAR(255) NOT NULL,
		password VARCHAR(255) NOT NULL,
		notif INT(9) NOT NULL,
		verify_pin VARCHAR(255) NOT NULL,
		reg_verify INT(9) NOT NULL)");
	echo "Table 'USERS' created successfully.<br>";

	$db->exec("CREATE TABLE IF NOT EXISTS images (img_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255) NOT NULL,
	image VARCHAR(255) NOT NULL)");
	echo "Table 'IMAGES' created successfully.<br>"; 

	$db->exec("CREATE TABLE IF NOT EXISTS comments (comment_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		image VARCHAR(255) NOT NULL,
		username VARCHAR(255) NOT NULL,
		comment TEXT NOT NULL)");
	echo "Table 'COMMENTS' created successfully.<br>";

	$db->exec("CREATE TABLE IF NOT EXISTS likes (likes_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(255) NOT NULL,
		image VARCHAR(255) NOT NULL)");
	echo "Table 'LIKES' created successfully.<br>";

	$db->exec("CREATE TABLE IF NOT EXISTS likescount (likes_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	image VARCHAR(255) NOT NULL,
	count INT(9) NOT NULL)");
echo "Table 'LIKESCOUNT' created successfully.<br><br><br>";

	$username = 'phumie';
    $mail = 'phumie.nevhutala@gmail.com';
    $password = password_hash('123456', PASSWORD_DEFAULT);
	$name = 'Phumudzo';
	$surname = 'Nevhutala';
	$notif = 0;
	$verify_pin = '1234';
	$reg_verify = 1;
	$stmt = $db->prepare('INSERT INTO users (username, email, name, surname, password, notif, verify_pin, reg_verify) VALUES (:username, :email, :name, :surname, :password, :notif, :verify_pin, :reg_verify)');
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
	$stmt->bindParam(':email', $mail, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
	$stmt->bindParam(':notif', $notif, PDO::PARAM_STR);
	$stmt->bindParam(':verify_pin', $verify_pin, PDO::PARAM_STR);
	$stmt->bindParam(':reg_verify', $reg_verify, PDO::PARAM_INT);
	$stmt->execute();
	echo "Table user updated.<br>";
} catch (PDOException $e) 
{
	echo $database.'<br>'.$e->getMessage();
}
$db = null;
?>
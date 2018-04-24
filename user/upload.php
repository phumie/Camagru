<?php
    require_once "../server/pdo.php";
    session_start();
    $username = $_SESSION['username'];

    //uploading selfie



?>

<script>
    //FOR MERGING SELFIE AND FILTER

    var c = document.getElementById("selfieCanvas");
    var ctx = c.getContext("2d");
    var image1 = new Image();
    var image2 = new Image();
    image1.src = "../images/filters/100.png";
    image1.onload = function()
    {
        ctx.drawImage(image1,0,0,3);
    }
</script>
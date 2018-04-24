<?php
   require_once "../server/pdo.php";
   session_start();

    try{

        if (!empty($_SESSION['username'])) 
        {
            if (isset($_POST['dbupload'])) {
                $username = $_SESSION['username'];
                $img = $_FILES['uploadimg']['name'];
                $tmp_name = $_FILES['uploadimg']['tmp_name'];
                $img_size = $_FILES['uploadimg']['size'];

                if (empty($img))
                {
                    echo "<script>alert('Please Select Image');</script>";
                }
                else
                {
                    $upload_dir = '../images/uploads';   
                    $img_ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                    $newimg = rand(1000, 10000).".".$img_ext;
        
                    if ($img_size < 5000000)
                    {
                        move_uploaded_file($tmp_name, "$upload_dir/$newimg");
                    }
                    else
                    {
                        echo "<script>alert('Your File is too Large');</script>";
                    }
                    $sql = "INSERT INTO images(image, username) VALUES(:image, :user)";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":image", $newimg);
                    $stmt->bindParam(":user", $username);
                    if ($stmt->execute())
                    {
                        echo "<script>alert('Image Upload Successful');</script>";

                        $count = 0;
                        $sql = "INSERT INTO likescount(image, count) VALUES (:image, :count)";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(":image", $newimg);
                        $stmt->bindParam(":count", $count);
                        $stmt->execute();
                    } 
                    else
                    {
                        echo "<script>alert('An Error Occured While Uploading. Please Try Again.');</script>";
                    }
                } 
            }
            $selfie_taken = 0;
            if (isset($_POST['takesnap'])) {
                
            }
            if (isset($_POST['upldselfie']))
            {
                if ($selfie_taken == 1){
                    $upload_dir = '../images/uploads';
                    $img = $_POST['hidden_data'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = $upload_dir . "selfie" . mktime() . ".png";
                    $success = file_put_contents($file, $data);
                    print $success ? $file : 'Unable to save the file.';
                }
                else{
                    echo "<script>alert('Take selfie before uploading');</script>"; 
                }
                
            }
                  
        }
        else
        {
            echo "<script>alert('You are not logged in.');</script>";
            ?>
            <html><meta http-equiv="refresh" content="1; url=../index.php"></html>
             <?php
            exit();
        }
    }
    catch (PDOException $e) 
    {
        echo '<br>'.$e->getMessage();
    }       
?>

<html>
    <head>
        <link href="../css/selfie.css" rel="stylesheet" type="text/css">
        <link href="../css/home.css" rel="stylesheet" type="text/css">
    </head>

    <body onload="disableBtn()">
        <div class="topnav">
            <a class="left" href="gallery.php"><img class="img" src="http://www.iconsdb.com/icons/preview/white/home-7-xxl.png" alt="HOME"></a>
            <a class="active" href="../server/logout.php"><img class="img" src="http://prosoundformula.com/wp-content/uploads/2015/04/logout-big-icon-200px.png" alt="LOGOUT"></a>
            <a href="../server/settings.php"><img class="img" src="http://flaticons.net/icons/Mobile%20Application/Settings-01.png" alt="SETTINGS"></a>
            <a href="camera.php"><img class="img" src="http://www.waltersgrovebaptistchurch.com/camera_01.png" "TAKEPHOTO"></a>
        </div>

        <div class="footer">
            <p>&copy Phumie Nevhutala 2017</p>  
        </div>

        <div class="mini_gallery">
            <p>UPLOAD HISTORY</p>
            <?php
                try{
                    $imagepath = "../images/uploads/";
                    $sql = "SELECT * FROM images WHERE username = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":username", $_SESSION['username']);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                    $image = $row['image'];
            ?>
                <div class="thumbnail">
                    <img class="gallery" src="<?php echo $imagepath.$image?>" alt="">
                </div>  
            <?php
                    }
                }
                catch (PDOException $e){
                    echo '<br>'.$e->getMessage();
                }
            ?>
                
        </div>

        <div class="snap_upload">
            <div class="canvas" >
                <body onload="init();">
                    <p>Take a Selfie</p>
                    <button class="selfiebutton" onclick="startWebcam();">Start WebCam</button>
                    <button name="takesnap" id="takesnap" class="selfiebutton" onclick="snapshot();"<?php ?>>Take Snapshot</button>
                    
                <form action="camera.php" method="post" enctype="multipart/form-data" id="image-form" accept-charset="utf-8">
                    <input name="hidden_data" id='hidden_data' type="hidden"/> <!-- canvas will be hidden in this input for upload to DB-->
                        
                    <button class="selfiebutton"  name="upldselfie" id="upldselfie">Upload Selfie</button> 
                    <video onclick="snapshot(this);" id="video" controls autoplay></video>         
                    <canvas  id="myCanvas" width="660" height="600"></canvas>
                        <script>
                            function convertCanvas() {
                                 var canvas = document.getElementById("myCanvas");
                                 var dataURL = canvas.toDataURL("image/png");
                                 document.getElementById("hidden_data").value = dataURL;
                            }

                            // onclick="convertCanvas()"
                            function disableBtn() {
                                document.getElementById("upldselfie").disabled=true;
                            }
                            // window.onload = disableBtn;
                        </script>
                </body>  
            </div>
                <div class="canvas">
                    <p>UPLOAD IMAGE</p>
                    <input type="file" name="uploadimg" id="uploadimg" accept="image/*" onchange="loadFile(event)">
                    <img id="output" width="20" height="20">
                    <!-- <input type="button" id="go" name="go" onclick="savesnap();"> -->
                            <script>
                                var loadFile = function(event)
                                {
                                    var output = document.getElementById('output');
                                    output.src = URL.createObjectURL(event.target.files[0]);
                                    
                                };
                            </script>
                    <button class="selfiebutton"  name="dbupload" id="dbupload"> Upload Image</button>
                    <canvas id="upload" name="upload"></canvas>
                </div>

                <div class="filters">
                    
                        <input type="radio" onclick="applyFilter();" name="filter" value="100.png"><img id="100.png" class="filter" src="../images/filters/100.png">
                        <input type="radio" onclick="applyFilter();" name="filter" value="hugging.png"><img id="hugging.png" class="filter" src="../images/filters/hugging.png">
                        <input type="radio" onclick="applyFilter();" name="filter" value="peace.png"><img id="peace.png" class="filter" src="../images/filters/peace.png">
                        <input type="radio" onclick="applyFilter();" name="filter" value="nerd.png"><img id="nerd.png" class="filter" src="../images/filters/nerd.png" >
                        <input type="radio" onclick="applyFilter();" name="filter" value="heart.png"><img id="heart.png" class="filter" src="../images/filters/heart.png">
                        <input type="radio" onclick="applyFilter();" name="filter" value="melon.png"><img id="melon.png" class="filter" src="../images/filters/melon.png">
                            <script>
                                function applyFilter() {
                                    var radios = document.getElementsByName('filter');

                                    for (var i = 0, length = radios.length; i < length; i++)
                                    {
                                        if (radios[i].checked)
                                        {
                                            var canvas = document.getElementById('upload');
                                            var context = canvas.getContext('2d');
                                            var img = document.getElementById(radios[i].value);
                                            var x = 200;
                                            var y = 100;
                                            var height = 50;
                                            var width = 50;
                                            context.drawImage(img, x, y, width, height);
                                            break;
                                        }
                                    }
                                }                       
                            </script>
                </div>
                
                <input type="hidden" id="image-url" name="image_url">
                <input type="hidden" id="watermark" name ="watermark">
            </form>
            
        </div>
    </body>

    </body>

<script>
      //--------------------
      // GET USER MEDIA CODE
      //--------------------
          navigator.getUserMedia = ( navigator.getUserMedia ||
                             navigator.webkitGetUserMedia ||
                             navigator.mozGetUserMedia ||
                             navigator.msGetUserMedia);

      var video;
      var webcamStream;

      function startWebcam() {
        if (navigator.getUserMedia) {
           navigator.getUserMedia (

              // constraints
              {
                 video: true,
                 audio: false
              },

              // successCallback
              function(localMediaStream) {
                  video = document.querySelector('video');
                 video.src = window.URL.createObjectURL(localMediaStream);
                 webcamStream = localMediaStream;
              },

              // errorCallback
              function(err) {
                 console.log("The following error occured: " + err);
              }
           );
        } else {
           console.log("getUserMedia not supported");
        }  
      }

      function stopWebcam() {
          webcamStream.stop();
      }
      //---------------------
      // TAKE A SNAPSHOT CODE
      //---------------------
      var canvas, ctx;

      function init() {
        // Get the canvas and obtain a context for
        // drawing in it
        canvas = document.getElementById("myCanvas");
        ctx = canvas.getContext('2d');
      }

      function snapshot() {
        document.getElementById("upldselfie").disabled=false;
        ctx.drawImage(video, 0,0, canvas.width, canvas.height);
      }

  </script>
</html>

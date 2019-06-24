<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <title>OCR Copy</title>
</head>
<body>
  <!-- Header -->
<div class="header">
  <img class="header-left" src="drdo.png" height="155" width="165">
  <div>
  <center>
    <h1 style="color: #09418C; font-family: Gotham Book,sans-serif;"><center>Instruments Research & Development Establishment</center></h1>
    <h3 style="color: #09418C; font-family: Gotham Book,sans-serif;"><center>HR Automation System</center></h3>
    <h3 style="color: #09418C; font-family: Gotham Book,sans-serif;"><center>Optical Character Recognition</center></h3>
  </center>
  </div>
</div>
<!-- /Header -->
<div align="center">
  <br><p><b>The cropped image is:</b></p><br>

<?php
require 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;

if(isset($_POST['submit'])) {

    if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {

        if(!file_exists('images')) {
            mkdir('images', 0755);
        }

        $filename = $_FILES['image']['name'];
        $filepath = 'images/'. $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $filepath);


        if(!file_exists('images/crop')) {
            mkdir('images/crop', 0755);
        }

        // crop image
        $img = Image::make($filepath);
        $croppath = 'images/crop/'. $filename;

        $img->crop($_POST['w'], $_POST['h'], $_POST['x1'], $_POST['y1']);
        $img->save($croppath);

       echo "<img src='". $croppath ."' />";
       shell_exec('"C:\\Program Files (x86)\\Tesseract-OCR\\tesseract" "C:\xampp\htdocs\OCR\images\crop\\'.$filename.'" out');
       echo "</br>";
       $myfile = fopen("out.txt", "r") or die("Unable to open file!");
?>

      <div><p align="center" id="p1" style="display:none;"><?php include('out.txt'); ?></p></div>
      <br>
      <button align="center" class="submit_input" onclick="copyToClipboard('#p1')">Copy</button>
      <script type="text/javascript">
        function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
      }
      </script>

<?php
       fclose($myfile);
       echo "</pre>";
       }
}
?>
</div>
</body>
</html>
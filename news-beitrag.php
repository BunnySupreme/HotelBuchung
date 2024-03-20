<?php
// Check if a valid user is logged in.

$adminMsg = '';
if (isset($_SESSION["usernameSession"]) && $_SESSION["usertyp"] == "admin")
{
    $adminMsg = 'Sie sind als Admin berechtigt, News-Beiträge zu errichten';
}
else
{
    $adminMsg = 'Keine Berechtigung!';
}

?>

<?php
//new
//timestamp
$date = new DateTime();
$timestamp = $date->getTimestamp();
//check if text got submitted
if (isset($_POST["submit"])){
    if (isset($_POST["news-beitrag"]) && !empty($_POST["news-beitrag"])) {
        $news_beitrag = $_POST["news-beitrag"];
    }
    if (isset($_POST["title"]) && !empty($_POST["title"])) {
        $title = $_POST["title"];
    }
}

$target_dir = 'uploads/news';
$file = @$_FILES["picture"];
$picname = explode(".", @$_FILES["picture"]["name"]);
$target_file = $target_dir .
$picname[0] . "_". $timestamp . "." . end($picname);
$acceptedtype = ["jpg", "jpeg", "png", "gif"];

//end new
$typeOk = 0;
$newsOk = 0;

if(isset($_FILES["picture"]))
{
    if($_FILES["picture"]["error"] == UPLOAD_ERR_OK)
    {
        if($_FILES["picture"]["type"] == "image/jpeg" || $_FILES["picture"]["type"] == "image/png")
        {
            $typeOk = 1;    //File type is ok
        }
        else
        {
            echo "Der Dateityp wird nicht unterstützt! <br>";
            $typeOk = 0;
        }
    }
    else
    {
        echo "Bitte eine Bilddatei hochladen <br>";
    }

    
}



if (isset($news_beitrag))
{
    $newsOk = 1;
}
else
{
    $newsOk = 0;
}

$titleOk = 0;

if (isset($title))
{
    $titleOk = 1;
}
else
{
    $titleOk = 0;
}

if(isset($_FILES["picture"]))
            {

                if($_FILES["picture"]["size"] <= 5000000)  //5 MB
                {
                    if ($typeOk == 1 && $newsOk == 1 && $titleOk == 1)
                    {
                        $uniqueId = uniqid() . "_" . $_FILES["picture"]["name"];
                        $destination = getcwd()."\uploads\\news\\" . $uniqueId; 
                        //new
                        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $destination)){
                            //hier könnte man allgemein Groesse vom Thumbnail ändern. Jetzt ist es 200
                            createThumbnail($destination, getcwd()."\uploads\\news\\thumbnail_" . $uniqueId , 200);
                            createDBentry($news_beitrag, "uploads\\news\\thumbnail_" . $uniqueId, $timestamp, $title);
                            
                           
                        }
                        //new end
                        
                        
                    }
                    else
                    {
                        if ($newsOk == 0)
                        {
                            echo "Bitte einen Text für den News-Beitrag schreiben <br>";
                        }
                        if ($titleOk == 0)
                        {
                            echo "Bitte einen Titel für den News-Beitrag schreiben <br>";
                        }
                    }
                }
                else
                {
                    echo "Die Datei ist zu groß! Bitte nicht größer als 5 MB";
                }
                
            }




function createDBentry($news_beitrag, $path, $timestamp, $title)
{
    
    require('dbaccess.php');
    $db_obj = new mysqli($host, $user, $password, $database);
    
    if ($db_obj->connect_error) {
        echo "Connection Error: " . $db_obj->connect_error;
        exit();
        }
        
    $sql = "INSERT INTO `news` (`path`, `text`, `timestamp`, `title`)
    VALUES (?, ?, ?, ?)";
    $stmt = $db_obj->prepare($sql);
    
    $stmt-> bind_param("ssis", $path, $news_beitrag, $timestamp, $title);
    if ($stmt->execute()){
        echo "Upload erfolgreich!";
    } $stmt-> close(); $db_obj->close();
}

function createThumbnail($sourceFile, $destFile, $maxSize) {
    list($width, $height, $type) = getimagesize($sourceFile);
    
    //neue Groessen berechnen
    $ratio = $width / $height;
    if ($width > $height) {
        $newWidth = $maxSize;
        $newHeight = $maxSize / $ratio;
    } else {
        $newWidth = $maxSize * $ratio;
        $newHeight = $maxSize;
    }
    //image resource erstellen, um damit weiterzuarbeiten
    //musste in php.ini in apache bei ;extension=gd semikolon entfernen, um comment zu entfernen
    $src = imagecreatefromstring(file_get_contents($sourceFile));
    //mache true-color image resource (mehr und bessere Farben)
    $dst = imagecreatetruecolor($newWidth, $newHeight);

    //kopiere rechteck vom Bild, und resize es. Speziell um Thumbnails zu machen
    // 0er sind destination und source koordinaten. 0,0 ist open links
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    //mache jetzt das image
    imagejpeg($dst, $destFile);

    //free memory
    imagedestroy($src);
    imagedestroy($dst);
}
    
?>



</br>
<div class ="containter">
    <div class ="row">
        <div class="form-news">
            <?php echo $adminMsg?>
            
            <form enctype="multipart/form-data" method="post" action="">
            <br/>
                <label for="picture">Bild hochladen. Unterstützte Datentypen: jpeg, png. Max Größe 5 MB.</label>
                <br/>
                <input type="file" name="picture" accept="image/jpeg">
                    </br>
                    </br>
                    <div class="form-group">
                        <label for="title">Titel eintragen</label>
                        <textarea class="form-control" name="title" id="title" rows="1S"></textarea>
                        <br/>
                        <label for="news-beitrag">News Text eintragen</label>
                        <textarea class="form-control" name="news-beitrag" id="news-beitrag" rows="5S"></textarea>
                    </div>
                    </br>
                    <input type="submit" name="submit" value="News Beitrag hochladen">
        </div>
        </form>
    </div>
</div>




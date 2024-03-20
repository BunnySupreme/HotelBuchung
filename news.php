<div class="overflow-auto body-home d-flex flex-column min-vh-100 bg-image-blur overflow-visible">
<!--
overflow-auto: if content larger than container, scrollbar appears
body-home: custom class we could implement
d-flex flexible box layout
flex-column: column, arranged vertically
min-vh-100: minimumt height 100% of viewport height
bg-image-blur: custom class we could implement for blur effect
overflow-visible: content that exceeds cotainer will be rendered outside container without scrollbar
-->

    <?php

    require("dbaccess.php");
    $db_obj = new mysqli($host, $user, $password, $database);
    if ($db_obj->connect_error) {
    echo "Connection Error: " . $db_obj->connect_error;
    exit();
    }

    if (empty(mysqli_connect_error())) { //no db error
        $sql = "SELECT id, path, text, timestamp, title FROM news ORDER BY timestamp DESC";
        $result = $db_obj->query($sql);

        if ($result->num_rows > 0) {
            echo '
            <div class="col-lg-8 container mx-auto">';
            //lg: larger screen, 8 oout of 12 available columns
            //mx-auto: left/right margin to auto, horizontally centers container with parent element
            
            //output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class = "col-lg-12 mt-4 ">
                <div class = "bg-dark pt-3 rounded">';
                //mt-4: margin size 4 at top of element
                //pt-3: padding size 3 to top
                // rounded: rounds corners
                if (!empty($row["path"])){
                    echo '<img class="img-fluid" src="' . $row["path"] . '" alt = "News image"> <br>';
                    //img-fluid: responsive img

                }
                echo '<br>
                        <div class = "lead fs-3 mt-3 p-2" style="background-color:#f6b47aec";><strong>' . $row["title"] . "</strong></div>";
                        //lead: makes text stand out(increased font size and line height)
                        //fs-3 could use as custom class to make font size larger
                        //strong: bold
                echo '<div class="text-start px-5 py-3 news-text lead fw-normal " style = "background-color: #f6b47aec; border-bottom-right-radius:0.5rem; border-bottom-left-radius:0.5rem; ">';
                //text-start: aligns text to left
                //news-text: potential css styling we could implement
                //fw-normal: reset font weight to normal
                echo $row["text"];
                echo "<hr>";

                //convert timestamp to date:
                $date = date("Y-m-d H:i:s", $row['timestamp']);

                echo '<p class="fs-6">veröffentlicht am ' . $date . '</p>';
                echo "</div>";
                echo "";
                echo '</div>';
            }

        }
    }

    
    ?>
</div>
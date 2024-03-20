<br>
<!--
<div class = form-buchen>
    <form action="" method="post" autocomplete="on">
        <label for="anreisedatum">Anreisedatum: </label>
        <input type="date" id="anreisedatum" name="anreisedatum" value="<?php //if(isset($_POST["anreisedatum"])) echo $_POST["anreisedatum"];?>" required></input></br></br>
        <label for="abreisedatum">Abreisedatum: </label>
        <input type="date" id="abreisedatum" name="abreisedatum" value="<?php //if(isset($_POST["abreisedatum"])) echo $_POST["abreisedatum"];?>" required></input></br></br>
        <input type="radio" id="Einzelzimmer" name="zimmer" value="Einzelzimmer"<?php //if(isset($_POST["zimmer"])  && $_POST["zimmer"] == "Einzelzimmer") echo 'checked="checked"'; ?> required></input>
        <label for="Einzelzimmer">Einzelzimmer (70€/Tag)</label></br>
        <input type="radio" id="Doppelzimmer" name="zimmer" value="Doppelzimmer"<?php //if(isset($_POST["zimmer"])  && $_POST["zimmer"] == "Doppelzimmer") echo 'checked="checked"'; ?> required></input>
        <label for="Doppelzimmer">Doppelzimmer (85€/Tag)</label></br></br>
        <input type="checkbox" id="fruehstueck" name="fruehstueck"></input>
        <label for="fruehstueck">Frühstück (Aufpreis 10€/Tag/Zimmer)</label></br>
        <input type="checkbox" id="parkplatz" name="parkplatz"></input>
        <label for="parkplatz">Parkplatz (Aufpreis 5€/Tag/Zimmer)</label></br>
        <input type="checkbox" id="haustiere" name="haustiere"></input>
        <label for="haustiere">Mitnahme von Haustieren (Aufpreis 5€/Tag/Zimmer)</label></br>

        <br/><br/>
        <button class="btn btn-lg btn-success btn-block" type="submit" name="buchen">Buchen</button>
    </form>
</div>
-->





<?php
require("dbaccess.php");
$db_obj = new mysqli($host, $user, $password, $database);
if ($db_obj->connect_error) {
echo "Connection Error: " . $db_obj->connect_error;
exit();
}
?>

<?php
$msg = '';
//select user id
$username = $_SESSION["usernameSession"];

// Use a prepared statement to prevent SQL injection
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $db_obj->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Now fetch results
    $result = $stmt->get_result();

    // Check if a row is returned
    if ($row = $result->fetch_assoc()) {
        $fk_userid_prepared = $row['id'];
    } else {
        // Handle the case where the username is not found
        // Maybe log error or redirect users
        echo "User not found!";
        exit();
    }
    $stmt->execute();
    $stmt->close();
} else {
    // Handle the case where the prepared statement fails
    // Maybe log error or redirect users
    echo "Error in prepared statement!";
    exit();
}
//select user_id finished

$sql = "INSERT INTO `buchungen` (`anreise`, `abreise`, `fruehstueck`, `parkplatz`, `haustiere`, `fk_userid`, `fk_znr`, `timestamp`, `rstatus`, `gesamtpreis`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $db_obj->prepare($sql);

// Calculate the total price based on room type and additional services
if(isset($_POST["zimmer"]))
{
    $roomPrice = ($_POST["zimmer"] == "Einzelzimmer") ? 70.00 : 85.00;
}
$fruehstueckPrice = (isset($_POST["fruehstueck"])) ? 10.00 : 0;
$parkplatzPrice = (isset($_POST["parkplatz"])) ? 5.00 : 0;
$haustierePrice = (isset($_POST["haustiere"])) ? 5.00 : 0;

if(isset($_POST["anreisedatum"]) && isset($_POST["abreisedatum"]) && isset($_POST["zimmer"]))
{
    $anreiseDate = new DateTime($_POST["anreisedatum"]);
    $abreiseDate = new DateTime($_POST["abreisedatum"]);
    $numberOfDays = $anreiseDate->diff($abreiseDate)->days;
    
    $gesamtpreis = ($roomPrice + $fruehstueckPrice + $parkplatzPrice + $haustierePrice) * $numberOfDays;
}




$stmt-> bind_param("ssiiiiissd", $anreise, $abreise, $fruehstueck, $parkplatz, $haustiere, $fk_userid, $fk_znr, $currentDate, $status, $gesamtpreis);

if(isset($_POST["anreisedatum"]) && isset($_POST["abreisedatum"]))
{
    if($_POST["anreisedatum"] >= $_POST["abreisedatum"])
    {
        $msg = "Das Anreisedatum darf nicht vor/an dem Abreisedatum sein!";
    }
    else
    {
        if(isset($_POST["zimmer"]))
        {
            $anreise = $_POST["anreisedatum"];
            $abreise = $_POST["abreisedatum"];


            if (isset($_POST["fruehstueck"]))
            {
                $fruehstueck = 1;
            }
            else
            {
                $fruehstueck = 0;
            }
            if (isset($_POST["parkplatz"]))
            {
                $parkplatz = 1;
            }
            else
            {
                $parkplatz = 0;
            }
            if (isset($_POST["haustiere"]))
            {
                $haustiere = 1;
            }
            else
            {
                $haustiere = 0;
            }

            $fk_userid = $_SESSION["userid"];
            
            //Code, um richtiges Zimmer auszuwählen
            //Subselect waehlt zimmer, die konflikte haben mit termin
            //wir waehlen dann ein zimmer (limit 1) wo kein Konflikt (NOT IN...)
            $sql_znr = "SELECT zimmern.zimmernr
            FROM zimmern
            LEFT JOIN buchungen ON zimmern.zimmernr = buchungen.fk_znr
            WHERE zimmertyp = ? AND zimmern.zimmernr NOT IN 
            (SELECT zimmern.zimmernr
            FROM zimmern
            LEFT JOIN buchungen ON zimmern.zimmernr = buchungen.fk_znr
            WHERE zimmertyp = ? AND (
                (? < abreise AND ? > anreise)
                OR
                (anreise < ? AND abreise > ?)
            )) LIMIT 1";
            $stmt_znr = $db_obj->prepare($sql_znr);

            if (!$stmt_znr) {
                echo "Fehler im prepared statement!";
                exit();
            }
            $stmt_znr->bind_param("ssssss", $_POST["zimmer"], $_POST["zimmer"], $anreise, $anreise, $abreise, $anreise);
            $stmt_znr->execute();
            $stmt_znr_result = $stmt_znr->get_result();

            if ($row_znr = $stmt_znr_result->fetch_assoc()) {
                $fk_znr = $row_znr['zimmernr'];
                $currentDate = date("Y-m-d H:i:s"); // Format: YYYY-MM-DD HH:MM:SS
                $status = "neu";

                $stmt_znr->close();

                //jetzt wo alles gecheckt, endlich execute vom main statement, zum Inserten
                $stmt->execute();
                $msg = $_POST["zimmer"] . " für den Zeitraum ". $anreise . " / " . $abreise ." erfolgreich gebucht!";
            } else {
                // Wenn kein Zimmer zu finden
                $msg = "Es ist leider kein " . $_POST["zimmer"] . " für diesen Zeitraum verfügbar.";
                //exit();
            }
            
            // statements closen
            //$stmt_znr->close();
            $stmt->close();
            
            $db_obj->close();
            
            //sleep(2);
            //header("Location: index.php?page=home");
        }
    }
}
?>

<?php
if (isset($_POST["usernameR"], $_POST["passwordR"], $_POST["email"], $_POST["vorname"], $_POST["nachname"], $_POST["anrede"])) {
    
}
?>



<h4 class="text-center"><?php echo $msg; ?></h4>

<div class="overflow-auto body-home d-flex flex-column min-vh-100 bg-image-blur">
    <div class="container d-flex justify-content-center h-75 align-items-center">
        <div class="card card-body p-4 bg-dark text-white" style="border-radius: 1rem; max-width: 650px;">
            <div class="form-buchen">
                <form action="" method="post" autocomplete="on">
                    <div class="form-group p-2 px-10">
                        <label for="anreisedatum" class="text-white lead p-1">Anreisedatum</label>
                        <input type="date" class="form-control" name="anreisedatum" id="anreisedatum" value="<?php if(isset($_POST["anreisedatum"])) echo $_POST["anreisedatum"];?>" required></input>
                    </div>

                    <div class="form-group p-2 px-10">
                        <label for="abreisedatum" class="text-white lead p-1">Abreisedatum</label>
                        <input type="date" class="form-control" name="abreisedatum" id="abreisedatum" value="<?php if(isset($_POST["abreisedatum"])) echo $_POST["abreisedatum"];?>" required></input>
                    </div>

                    <div class="form-group p-2 px-10">
                        <label for="zimmer" class="text-white lead p-1">Zimmertyp </label>
                        <select class="form-select" name="zimmer" id="zimmer" required>
                            <option id="Einzelzimmer" name="zimmer" value="Einzelzimmer" <?php echo (isset($_POST['zimmer']) && $_POST['zimmer'] == 'Einzelzimmer') ? 'selected' : ''; ?>>Einzelzimmer (70€/Tag)</option>
                            <option id="Doppelzimmer" name="zimmer" value="Doppelzimmer" <?php echo (isset($_POST['zimmer']) && $_POST['zimmer'] == 'Doppelzimmer') ? 'selected' : ''; ?>>Doppelzimmer (85€/Tag)</option>
                        </select>
                    </div>

                    <div class="form-group p-2 px-10">
                        <input type="checkbox" class="form-check-input lead" style="float:none !important;" name="fruehstueck" id="fruehstueck">
                        <label class="form-check-label text-white lead">Frühstück (Aufpreis 10€/Tag/Zimmer)</label>
                    </div>

                    <div class="form-group p-2 px-10">
                        <input type="checkbox" class="form-check-input lead" style="float:none !important;" name="parkplatz" id="parkplatz">
                        <label class="form-check-label text-white lead">Parkplatz (Aufpreis 5€/Tag/Zimmer)</label>
                    </div>

                    <div class="form-group p-2 px-10">
                        <input type="checkbox" class="form-check-input lead" style="float:none !important;" name="haustiere" id="haustiere">
                        <label class="form-check-label text-white lead">Mitnahme von Haustieren (Aufpreis 5€/Tag/Zimmer)</label>
                    </div>

                    <div class="form-group text-center p-2 px-5">
                        <button class="btn btn-lg btn-success btn-block" type="submit" name="buchen">Buchen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

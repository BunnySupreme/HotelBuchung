<?php
require("dbaccess.php");
$db_obj = new mysqli($host, $user, $password, $database);
if ($db_obj->connect_error) {
    echo "Connection Error: " . $db_obj->connect_error;
    exit();
}

/*
// Debugging: Print the values of $_GET and $_SESSION
echo "<pre>";
print_r($_GET);
print_r($_SESSION);
echo "</pre>";
*/


// Check if a column to sort is specified
$orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'timestamp';
$order = $orderBy == 'timestamp' && !isset($_GET['order']) ? 'DESC' : (isset($_GET['order']) && strtoupper($_GET['order']) == 'DESC' ? 'DESC' : 'ASC');
//$order = isset($_GET['order']) && strtoupper($_GET['order']) == 'DESC' ? 'DESC' : 'ASC';

$sql = "SELECT *
        FROM buchungen 
        JOIN zimmern ON zimmern.zimmernr = buchungen.fk_znr
        JOIN users ON users.id = fk_userid
        ORDER BY $orderBy $order";
$result = $db_obj->query($sql);


/*
echo "<ul>";
while ($row = $result->fetch_array()) { 
    echo "<li>" . $row['username'] . ": " . $row['anreise'] . " - " . $row['abreise'] .  ": " . $row['zimmertyp'] . ", Zimmernummer: " . $row['zimmernr'] . "</li>";
}
echo "</ul>";
*/
?>

<table>
    <tr>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'bnr', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Buchungsnr</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'username', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Username</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'anreise', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Anreise</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'abreise', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Abreise</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'zimmernr', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Zimmer</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'fruehstueck', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Frühstück</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'parkplatz', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Parkplatz</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'haustiere', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Haustiere</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'gesamtpreis', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Gesamtpreis</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'rstatus', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Status</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'timestamp', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Buchungs-Datum</a></th>
        <th scope="col">Details</th>
        <th></th>
    </tr>

    <?php
    while ($row = $result->fetch_array()) {
    ?>
        <tr>
            <form style="border-radius:30%" method="POST" action="">
                <td><?php echo $row['bnr']; ?></td>
                <td><a class="nav-link" href="index.php?page=reservierungen_einzeln&user=<?php echo $row['id']; ?>"><?php echo $row['username']; ?></a></td>
                

                <td><input type="text" name="anreiseNeu" id="anreiseNeu" value="<?php echo $row['anreise']; ?>" class="form-control"></td>
                <td><input type="text" name="abreiseNeu" id="abreiseNeu" value="<?php echo $row['abreise']; ?>" class="form-control"></td>
                
                <td>
                    <select class="form-select" name="zimmernr" id="v" required>
                        <?php echo ($row['zimmernr'] == 1) ? '<option value= 1 selected>' : '<option value= 1>';?>1: Einzelzimmer</option>
                        <?php echo ($row['zimmernr'] == 2) ? '<option value= 2 selected>' : '<option value= 2>';?>2: Einzelzimmer</option>
                        <?php echo ($row['zimmernr'] == 3) ? '<option value= 3 selected>' : '<option value= 3>';?>3: Einzelzimmer</option>
                        <?php echo ($row['zimmernr'] == 4) ? '<option value= 4 selected>' : '<option value= 4>';?>4: Doppelzimmer</option>
                        <?php echo ($row['zimmernr'] == 5) ? '<option value= 5 selected>' : '<option value= 5>';?>5: Doppelzimmer</option>
                        <?php echo ($row['zimmernr'] == 6) ? '<option value= 6 selected>' : '<option value= 6>';?>6: Doppelzimmer</option>
                    </select>
                </td>
                <td>
                    <select class="form-select" name="fruehstueck" id="fruehstueck" required>
                        <?php echo ($row['fruehstueck'] == 1) ? '<option value="Ja" selected>' : '<option value="Ja">'; ?>Ja</option>
                        <?php echo ($row['fruehstueck'] == 0) ? '<option value="Nein" selected>' : '<option value="Nein">'; ?>Nein</option>
                    </select>
                </td>

                <td>
                    <select class="form-select" name="parkplatz" id="parkplatz" required>
                        <?php echo ($row['parkplatz'] == 1) ? '<option value="Ja" selected>' : '<option value="Ja">'; ?>Ja</option>
                        <?php echo ($row['parkplatz'] == 0) ? '<option value="Nein" selected>' : '<option value="Nein">'; ?>Nein</option>
                    </select>
                </td>

                <td>
                    <select class="form-select" name="haustiere" id="haustiere" required>
                        <?php echo ($row['haustiere'] == 1) ? '<option value="Ja" selected>' : '<option value="Ja">'; ?>Ja</option>
                        <?php echo ($row['haustiere'] == 0) ? '<option value="Nein" selected>' : '<option value="Nein">'; ?>Nein</option>
                    </select>
                </td>
                
                <td><?php echo $row['gesamtpreis']; ?></td>

                <td>
                    <select class="form-select" name="buchungsstatus" id="buchungsstatus" required>
                        <?php echo ($row['rstatus'] == 'bestätigt') 
                            ? '<option value="neu">neu</option>
                            <option value="bestätigt" selected>bestätigt</option>
                            <option value="storniert">storniert</option>' 
                            : (($row['rstatus'] == 'storniert') 
                                ? '<option value="neu">neu</option>
                                <option value="bestätigt">bestätigt</option>
                                <option value="storniert" selected>storniert</option>' 
                                : '<option value="neu" selected>neu</option>
                                <option value="bestätigt">bestätigt</option>
                                <option value="storniert">storniert</option>');
                        ?>
                    </select>
                </td>

                <td><?php echo $row['timestamp']; ?></td>
                <input type="hidden" name="bnr" value="<?php echo $row['bnr']; ?>">
                <input type="hidden" name="anreise" value="<?php echo $row['anreise']; ?>">
                <input type="hidden" name="abreise" value="<?php echo $row['abreise']; ?>">
                <td>
                    <a class="nav-link" href="index.php?page=reservierung_details&reservierung=<?php echo $row['bnr']; ?>">Details</a>
                </td>
                <td><button type="submit" name="submit" class="btn btn-primary">Save</button></td>
            </form>
        </tr>
    <?php
    }
    ?>

    <?php
        // form has been submitted, process the data
        if (isset($_POST['submit'])) {
            if($_POST['anreiseNeu'] > $_POST['abreiseNeu'])
            {
                echo "Das Anreisedatum darf nicht vor dem Abreisedatum sein!";
            }
            else
            {
            
            // retrieve form data
            $zimmernr = $_POST['zimmernr'];
            if ($zimmernr>=1 && $zimmernr<=3)
            {
                $zimmertyp = "Einzelzimmer";
            }
            else
            {
                $zimmertyp = "Doppelzimmer";
            }
            $anreise = $_POST['anreiseNeu'];
            $abreise = $_POST['abreiseNeu'];
            $fruehstueck = ($_POST['fruehstueck'] === 'Ja') ? 1 : 0;
            $parkplatz = ($_POST['parkplatz'] === 'Ja') ? 1 : 0;
            $haustiere = ($_POST['haustiere'] === 'Ja') ? 1 : 0;
            $status = $_POST['buchungsstatus'];
            $bnr = $_POST['bnr'];

            // change total price
            $roomPrice = ($zimmertyp == "Einzelzimmer") ? 70.00 : 85.00;
            $fruehstueckPrice = ($_POST["fruehstueck"] == "Ja") ? 10.00 : 0;
            $parkplatzPrice = ($_POST["parkplatz"] == "Ja") ? 5.00 : 0;
            $haustierePrice = ($_POST["haustiere"] == "Ja") ? 5.00 : 0;

            $anreiseDate = new DateTime($anreise);
            $abreiseDate = new DateTime($abreise);
            $numberOfDays = $anreiseDate->diff($abreiseDate)->days;

            $gesamtpreis = ($roomPrice + $fruehstueckPrice + $parkplatzPrice + $haustierePrice) * $numberOfDays;

            

            // create prepared statement
            $query = "UPDATE buchungen 
            SET anreise = ?, abreise = ?, fruehstueck = ?, parkplatz = ?, haustiere = ?, rstatus = ?, gesamtpreis = ?, fk_znr = ?
            WHERE bnr = ?";

            $stmt = mysqli_prepare($db_obj, $query);
            mysqli_stmt_bind_param($stmt, "ssiiisdii", $anreise, $abreise, $fruehstueck, $parkplatz, $haustiere, $status, $gesamtpreis, $zimmernr, $bnr);

            
            //check if new reservation is possible
            $sql_znr = "SELECT zimmern.zimmernr
            FROM zimmern
            LEFT JOIN buchungen ON zimmern.zimmernr = buchungen.fk_znr
            WHERE zimmern.zimmernr = ? AND zimmern.zimmernr NOT IN 
            (SELECT zimmern.zimmernr
            FROM zimmern
            LEFT JOIN buchungen ON zimmern.zimmernr = buchungen.fk_znr
            WHERE NOT bnr = ? AND zimmern.zimmernr = ? AND (
                (? < abreise AND ? > anreise)
                OR
                (anreise < ? AND abreise > ?)
            )) LIMIT 1";
            $stmt_znr = $db_obj->prepare($sql_znr);

            if (!$stmt_znr) {
                echo "Fehler im prepared statement!";
                exit();
            }
            $stmt_znr->bind_param("sisssss", $zimmernr, $bnr, $zimmernr, $anreise, $anreise, $abreise, $anreise);
            $stmt_znr->execute();
            $stmt_znr_result = $stmt_znr->get_result();

            if ($row_znr = $stmt_znr_result->fetch_assoc()) {
                $fk_znr = $row_znr['zimmernr'];
                $currentDate = date("Y-m-d H:i:s"); // Format: YYYY-MM-DD HH:MM:SS

                $stmt_znr->close();

                //jetzt wo alles gecheckt, endlich execute vom main statement, zum Inserten
                // execute prepared statement
                mysqli_stmt_execute($stmt);
                $affected_rows = mysqli_stmt_affected_rows($stmt);
                if ($affected_rows == 1)
                {
                    echo "Buchung " . $bnr ." erfolgreich bearbeitet!";
                }
            } else {
                // Wenn kein Zimmer zu finden
                echo "Zimmer " . $zimmernr . " ist leider für diesen Zeitraum nicht verfügbar.";
                //exit();
            }
            $affected_rows = mysqli_stmt_affected_rows($stmt);

            

            if ($affected_rows == 1) {
                echo '<meta http-equiv="Refresh" content="3;./index.php?page=reservierungs-verwaltung">';
                echo '<p class="bg-success lead px-5">Der Benutzer wurde erfolgreich bearbeitet!</p>';
                mysqli_stmt_close($stmt);
            } else {
                $page = $_SERVER['PHP_SELF'];
                echo '<meta http-equiv="Refresh" content="3;./index.php?page=reservierungs-verwaltung">';
                echo '<p class="bg-danger">Benutzer nicht bearbeitet!</p>';
                mysqli_stmt_close($stmt);
            }
        }
    }
    ?>
</table>


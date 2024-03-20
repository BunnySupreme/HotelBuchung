<?php
require("dbaccess.php");
$db_obj = new mysqli($host, $user, $password, $database);
if ($db_obj->connect_error) {
    echo "Connection Error: " . $db_obj->connect_error;
    exit();
}

// Check if a column to sort is specified
$orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'anreise';
$order = isset($_GET['order']) && strtoupper($_GET['order']) == 'DESC' ? 'DESC' : 'ASC';

if(isset($_GET['reservierung']))
{
    $bnr = $_GET['reservierung'];
}

$sql = "SELECT *
    FROM buchungen 
    JOIN zimmern ON zimmern.zimmernr = buchungen.fk_znr 
    JOIN users ON users.id = fk_userid
    WHERE bnr = $bnr
    ORDER BY $orderBy $order";

$result = $db_obj->query($sql);

/*
echo "<ul>";
while ($row = $result->fetch_array()) { 
    echo "<li>" . $row['anreise'] . " - " . $row['abreise'] .  ": " . $row['zimmertyp'] . ", Zimmernummer: " . $row['zimmernr'] . "</li>";
}
echo "</ul>";
*/
?>

<table>
    <tr>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'username', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Username</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'anreise', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Anreise</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'abreise', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Abreise</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'zimmertyp', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Zimmertyp</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'zimmertyp', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Zimmernummer</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'fruehstueck', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Frühstück</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'parkplatz', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Parkplatz</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'haustiere', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Haustiere</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'gesamtpreis', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Gesamtpreis in €</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'rstatus', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Status</a></th>
        <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'timestamp', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Datum</a></th>
        <th></th>
    </tr>
    <?php
    while ($row = $result->fetch_array()) {
        echo 
            "<tr><td>" . $row['username'] . "</td>
            <td>" . $row['anreise'] . "</td>
            <td>" . $row['abreise'] .  "</td>
            <td>" . $row['zimmertyp'] . "</td>
            <td>" . $row['zimmernr'] . "</td>";
            $anreiseDate = new DateTime($row['anreise']);
            $abreiseDate = new DateTime($row['abreise']);
            $zimmertyp = $row['zimmertyp'];
    ?>
        
            <form style="border-radius:30%" method="POST" action="">
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
                <td><button type="submit" name="submit" class="btn btn-primary">Save</button></td>
            </form>
        </tr>
    <?php
    }
    ?>
<?php
        // form has been submitted, process the data
        if (isset($_POST['submit'])) {
            
            // retrieve form data
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

            
            $numberOfDays = $anreiseDate->diff($abreiseDate)->days;

            $gesamtpreis = ($roomPrice + $fruehstueckPrice + $parkplatzPrice + $haustierePrice) * $numberOfDays;

            

            // create prepared statement
            $query = "UPDATE buchungen 
            SET fruehstueck = ?, parkplatz = ?, haustiere = ?, rstatus = ?, gesamtpreis = ?
            WHERE bnr = ?";

            $stmt = mysqli_prepare($db_obj, $query);
            mysqli_stmt_bind_param($stmt, "ssssii", $fruehstueck, $parkplatz, $haustiere, $status, $gesamtpreis, $bnr);

            
            
            // execute prepared statement
            mysqli_stmt_execute($stmt);
                
            $affected_rows = mysqli_stmt_affected_rows($stmt);

            

            if ($affected_rows == 1) {
                echo '<meta http-equiv="Refresh" content="3;./index.php?page=reservierung_details&reservierung=' . $bnr . '">';
                echo '<p class="bg-success lead px-5">Zimmer-Details geändert!</p>';
                mysqli_stmt_close($stmt);
            } else {
                $page = $_SERVER['PHP_SELF'];
                echo '<meta http-equiv="Refresh" content="3;./index.php?page=reservierung_details&reservierung=' . $bnr . '">';
                echo '<p class="bg-danger">Zimmer-Details nicht bearbeitet!</p>';
                mysqli_stmt_close($stmt);
            }
        }
    ?>
</table>


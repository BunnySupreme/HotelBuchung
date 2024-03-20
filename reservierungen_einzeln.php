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

if(isset($_GET['user']))
{
    $selectedUser = $_GET['user'];
}

$sql = "SELECT *
    FROM buchungen 
    JOIN zimmern ON zimmern.zimmernr = buchungen.fk_znr 
    JOIN users ON users.id = fk_userid
    WHERE fk_userid = $selectedUser
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
    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'zimmernr', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Zimmernummer</a></th>
    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'fruehstueck', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Frühstück</a></th>
    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'parkplatz', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Parkplatz</a></th>
    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'haustiere', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Haustiere</a></th>
    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['order_by' => 'status', 'order' => $order == 'ASC' ? 'DESC' : 'ASC'])); ?>">Status</a></th>
    </tr>
    <?php 
        while ($row = $result->fetch_array()) {
            echo 
            "<tr><td>" . $row['username'] . "</td>
            <td>" . $row['anreise'] . "</td>
            <td>" . $row['abreise'] .  "</td>
            <td>" . $row['zimmertyp'] . "</td>
            <td>" . $row['zimmernr'] . "</td>
            <td>" . ($row['fruehstueck'] == 1 ? 'Ja' : 'Nein') . "</td>
            <td>" . ($row['parkplatz'] == 1 ? 'Ja' : 'Nein') . "</td>
            <td>" . ($row['haustiere'] == 1 ? 'Ja' : 'Nein') . "</td>
            <td>" . $row['status'] . "</td></tr>";
        }
    ?>
</table>
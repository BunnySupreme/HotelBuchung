<?php

    require("dbaccess.php");
    $db_obj = new mysqli($host, $user, $password, $database);
    if ($db_obj->connect_error) {
    echo "Connection Error: " . $db_obj->connect_error;
    exit();
    }

?>

<div>
    <div>
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Vorname</th>
                    <th scope="col">Nachname</th>
                    <th scope="col">Username</th>
                    <th scope="col">Neues Passwort</th>
                    <th scope="col">Email</th>
                    <th scope="col">Anrede</th>
                    <th scope="col">Status</th>
                    <th scope="col">Typ</th>
                    <th scope="col">Reservierungen</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT id, username, password, useremail, vorname, nachname, anrede, status, typ FROM users";
                    $results = $db_obj->query($sql);
                    if ($results->num_rows > 0) {
                        while ($line = $results->fetch_assoc()) {
                  ?>
                    <tr>
                      <form style="border-radius:30%" method="POST" action="">
                        <!-- all the ids have Adm as postfix because otherwise it gets confused with the login POST values -->
                        <td>
                          <input type="text" name="idAdm" id="idAdm" value="<?php echo $line['id']; ?>" class="form-control" readonly>
                        </td>
                        <td>
                          <input type="text" name="vornameAdm" id="vornameAdm" value="<?php echo $line['vorname']; ?>" class="form-control">
                        </td>
                        <td>
                          <input type="text" name="nachnameAdm" id="nachnameAdm" value="<?php echo $line['nachname']; ?>" class="form-control">
                        </td>
                        <td>
                          <input type="text" name="usernameAdm" id="usernameAdm" value="<?php echo $line['username']; ?>" class="form-control">
                        </td>
                        <td>
                          <input type="password" name="passwordAdm" id="passwordAdm" class="form-control">
                        </td>
                        <td>
                          <input type="text" name="useremailAdm" id="useremailAdm" value="<?php echo $line['useremail']; ?>" class="form-control">
                        </td>
                        <td>
                          <input type="text" name="anredeAdm" id="anredeAdm" value="<?php echo $line['anrede']; ?>" class="form-control">
                        </td>
                        
                        <td>
                            <select name="statusAdm" id="statusAdm" class="form-select" aria-label="Default select example">
                            <option selected><?php echo $line['status']; ?></option>

                            <?php
                            if ($line['status'] == "aktiv") {
                                echo '<option value="inaktiv">inaktiv</option>';
                            } else if ($line['status'] == "inaktiv") echo ' <option value="aktiv">aktiv</option>';
                            else {
                                echo ' <option value="aktiv">aktiv</option>';
                                echo '<option value="inaktiv">inaktiv</option>';
                            }
                            ?>
                            </select>
                        </td>
                        <td>
                            <select name="typAdm" id="typAdm" class="form-select" aria-label="Default select example">
                            <option selected><?php echo $line['typ']; ?></option>

                            <?php
                            if ($line['typ'] == "admin") {
                                echo '<option value="user">user</option>';
                            } else if ($line['typ'] == "user") echo ' <option value="admin">admin</option>';
                            else {
                                echo ' <option value="user">user</option>';
                                echo '<option value="admin">admin</option>';
                            }
                            ?>
                            </select>
                        </td>
                        <td>
                            <a class="nav-link" href="index.php?page=reservierungen_einzeln&user=<?php echo $line['id']; ?>">Reservierungen</a>
                        </td>

                        

                        <td>
                          <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        </td>
                      </form>
                    </tr>
                <?php
                        }
                    }
                ?>


  
                    
  
            </tbody>
        </table>
    </div>
</div>

<?php
  if (isset($_POST['submit'])) {
    // form has been submitted, process the data

    // retrieve form data
    $vorname = $_POST['vornameAdm'];
    $nachname = $_POST['nachnameAdm'];
    $username = $_POST['usernameAdm'];
    $useremail = $_POST['useremailAdm'];
    $password = $_POST['passwordAdm'];
    $status = $_POST['statusAdm'];
    $anrede = $_POST['anredeAdm'];
    $typ = $_POST['typAdm'];
    $id = $_POST['idAdm'];

    // Check if the password field is not empty
    if (!empty($password)) {
        // hash the password using the password_hash function
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // If password is not provided in the form, retain the existing password
        $hashedPassword = null;
    }

    // create prepared statement
    $query = "UPDATE users SET vorname = ?, nachname = ?, username = ?, useremail = ?, status = ?, anrede = ?, typ = ?";

    // Add password update only if a new password is provided
    if (!empty($password)) {
        $query .= ", password = ?";
    }    
    
    $query .= " WHERE id = ?";

    $stmt = mysqli_prepare($db_obj, $query);

    // bind parameters
    if (!empty($password)) {
      mysqli_stmt_bind_param($stmt, "ssssssssi", $vorname, $nachname, $username, $useremail, $status, $anrede, $typ, $hashedPassword, $id);
    } else {
      mysqli_stmt_bind_param($stmt, "sssssssi", $vorname, $nachname, $username, $useremail, $status, $anrede, $typ, $id);
    }

    // execute prepared statement
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
   
    if ($affected_rows == 1) {
      
      echo '<meta http-equiv="Refresh" content="2;./index.php?page=admin">';
      echo '<p class="bg-success lead px-5">Der Benutzer wurde erfolgreich bearbeitet!</p>';
      mysqli_stmt_close($stmt);
    } else {
      $page = $_SERVER['PHP_SELF'];
      echo '<meta http-equiv="Refresh" content="2;./index.php?page=admin">';
      echo '<p class="bg-danger">Benutzer nicht bearbeitet!</p>';
      mysqli_stmt_close($stmt);
    }


  }
 
 ?>
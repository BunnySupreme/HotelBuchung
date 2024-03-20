<?php
  require("dbaccess.php");
  $db_obj = new mysqli($host, $user, $password, $database);
  if ($db_obj->connect_error) {
  echo "Connection Error: " . $db_obj->connect_error;
  exit();
  }
  
  // logged in user information
  $sql = "SELECT * FROM users WHERE username = '{$_SESSION['usernameSession']}'";
  $result = $db_obj->query($sql);
  $row = $result->fetch_array()
?>

<br>
<div>
  <p>Profilfoto</p>
  <img id="profileImage" src="img/user_icon.png" alt="Profile Picture" style="width: 200px; height: 200px;"><br><br>
  <input type="file" id="fileInput" accept="image/*">
  <label for="fileInput">
    <button id="customButton">Profilfoto ändern</button>
  </label>
</div>

<br>

<div>
<p>Profildaten</p>

<nav class="navbar bg-body-tertiary" style="margin-left: -10px;">
  <form class="container-fluid" action="update_user_info.php" method="post">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon1" style="min-width: 150px;">Vorname</span>
      <input type="text" name="newVorname" class="form-control" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-top-right-radius: 0; border-bottom-right-radius: 0;" value="<?php echo $row['vorname'];?>">
      <span class="input-group-text rounded-end" style="border-top-left-radius: 0; border-bottom-left-radius: 0; height: 38px; background-color: #7a7a7a;">
        <button type="submit"><img src="img/edit_icon.png" alt="ändern" style="width: 30px; height: 30px; background-color: #7a7a7a;"></button>
      </span>
    </div>
  </form>
  <?php
    if (isset($_SESSION['vorname_update_success'])) {
      echo '<br><br><div style="margin-left: 10px; color: #39FF14;">' . $_SESSION['vorname_update_success'] . '</div>';
      unset($_SESSION['vorname_update_success']); // clear the message after displaying
    }
  ?>
</nav>

<nav class="navbar bg-body-tertiary" style="margin-left: -10px;">
  <form class="container-fluid" action="update_user_info.php" method="post">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon1" style="min-width: 150px;">Nachname</span>
      <input type="text" name="newNachname" class="form-control" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-top-right-radius: 0; border-bottom-right-radius: 0;" value="<?php echo $row['nachname'];?>">
      <span class="input-group-text rounded-end" style="border-top-left-radius: 0; border-bottom-left-radius: 0; height: 38px; background-color: #7a7a7a;">
        <button type="submit"><img src="img/edit_icon.png" alt="ändern" style="width: 30px; height: 30px; background-color: #7a7a7a;"></button>
      </span>
    </div>
  </form>
  <?php
    if (isset($_SESSION['nachname_update_success'])) {
      echo '<br><br><div style="margin-left: 10px; color: #39FF14;">' . $_SESSION['nachname_update_success'] . '</div>';
      unset($_SESSION['nachname_update_success']); // clear the message after displaying
    }
  ?>
</nav>

<nav class="navbar bg-body-tertiary" style="margin-left: -10px;">
  <form class="container-fluid" action="update_user_info.php" method="post">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon1" style="min-width: 150px;">Email Adresse</span>
      <input type="text" name="newEmail" class="form-control" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-top-right-radius: 0; border-bottom-right-radius: 0;" value="<?php echo $row['useremail'];?>">
      <span class="input-group-text rounded-end" style="border-top-left-radius: 0; border-bottom-left-radius: 0; height: 38px; background-color: #7a7a7a;">
        <button type="submit"><img src="img/edit_icon.png" alt="ändern" style="width: 30px; height: 30px; background-color: #7a7a7a;"></button>
      </span>
    </div>
  </form>
  <?php
    if (isset($_SESSION['email_update_success'])) {
      echo '<br><br><div style="margin-left: 10px; color: #39FF14;">' . $_SESSION['email_update_success'] . '</div>';
      unset($_SESSION['email_update_success']); // clear the message after displaying
    }
  ?>
</nav>

<nav class="navbar bg-body-tertiary" style="margin-left: -10px;">
  <form class="container-fluid" action="update_user_info.php" method="post">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon1" style="min-width: 150px;">Username</span>
      <input type="text" name="newUsername" class="form-control" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-top-right-radius: 0; border-bottom-right-radius: 0;" value="<?php echo $row['username'];?>">
      <span class="input-group-text rounded-end" style="border-top-left-radius: 0; border-bottom-left-radius: 0; height: 38px; background-color: #7a7a7a;">
        <button type="submit"><img src="img/edit_icon.png" alt="ändern" style="width: 30px; height: 30px; background-color: #7a7a7a;"></button>
      </span>
    </div>
  </form>
  <?php
    if (isset($_SESSION['username_update_success'])) {
      echo '<br><br><div style="margin-left: 10px; color: #39FF14;">' . $_SESSION['username_update_success'] . '</div>';
      unset($_SESSION['username_update_success']); // clear the message after displaying
    }
  ?>
</nav>

<!--- display hashed password (for testing)
<nav class="navbar bg-body-tertiary" style="margin-left: -10px;">
  <form class="container-fluid">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon1" style="min-width: 150px;">Passwort</span>
      <input type="text" class="form-control rounded-end" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" value="<?php echo $row['password'];?>">
    </div>
  </form>
</nav>
-->

<br>
<p>Passwort ändern</p>

<form class="container-fluid" action="change_password.php" method="post">
  <nav class="navbar bg-body-tertiary" style="margin-left: -13px;">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon1" style="min-width: 240px;">Altes Passwort</span>
      <input type="password" name="oldPassword" class="form-control rounded-end" style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" value="">
    </div>
  </nav>

  <nav class="navbar bg-body-tertiary" style="margin-left: -13px;">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon1" style="min-width: 240px;">Neues Passwort</span>
      <input type="password" name="newPassword" class="form-control rounded-end" style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" value="">
    </div>
  </nav>

  <nav class="navbar bg-body-tertiary" style="margin-left: -13px;">
    <div class="input-group d-flex align-items-center rounded" style="max-width: 500px;">
      <span class="input-group-text" id="basic-addon2" style="min-width: 240px;">Neues Passwort wiederholen</span>
      <input type="password" name="newPasswordAgain" class="form-control rounded-end" style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" value="">
    </div>
  </nav>
  <?php
    if (isset($_SESSION['password_update_error'])) {
      echo '<div style="margin-left: -10px; color: red;">' . $_SESSION['password_update_error'] . '</div><br>';
      unset($_SESSION['password_update_error']); // clear the message after displaying
    } else if (isset($_SESSION['password_update_success'])) {
      echo '<div style="margin-left: -10px; color: #39FF14;">' . $_SESSION['password_update_success'] . '</div><br>';
      unset($_SESSION['password_update_success']); // clear the message after displaying
    }
  ?>

  <div><button class="btn btn-lg btn-success btn-block" type="submit" style="margin-left: -13px;">Passwort ändern</button></div>
</form>

<br>

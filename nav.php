<?php ?>
<!-- navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="border-radius: 15px;"> <!-- navbar bg-dark color is #212529 -->
  <div class="container-fluid">
    <!--<a class="navbar-brand" href="#">Navbar</a>-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Home Link-->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php?page=home">Home</a>
        </li>

        <!-- Die Links -->
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=impressum">Impressum</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=help">Hilfeseite</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=news">News</a>
        </li>

        <!-- if logged in as admin -->
        <?php if (isset($_SESSION["usernameSession"]) && $_SESSION["usertyp"] == "admin") { ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=admin">Users verwalten</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=news-beitrag">News-Beitrag erstellen</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=reservierungs-verwaltung">Reservierungen verwalten</a>
          </li>
        <?php } ?>
        
        <!-- if logged in -->
        <?php if (isset($_SESSION["usernameSession"])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=buchen">Zimmer buchen</a>
          </li>
          </ul>
          
          <!--
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=logout">Ausloggen</a>
          </li>
          -->
          
          <!--
          <li class="nav-item ms-auto">
            <a class="nav-link"><img src="img/user_icon.png" alt="Profile Picture" style="width: 25px; height: 25px;"> <?php //echo $_SESSION["usernameSession"]; ?></a>
          </li>
          -->

        <?php } else { ?>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=registrierung">Registrieren</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=login">Einloggen</a>
          </li>
        </ul>
        <?php } ?>
      
      
      <?php if (isset($_SESSION["usernameSession"])) { ?>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="img/user_icon.png" alt="Profile Picture" style="width: 25px; height: 25px;">
              <?php echo $_SESSION["usernameSession"]; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="index.php?page=profile">Profil verwalten</a></li>
              <li><a class="dropdown-item" href="index.php?page=reservierungen">Meine Reservierungen</a></li>
              <div class="dropdown-divider"></div>
              <li><a class="dropdown-item" href="index.php?page=logout">Ausloggen</a></li>
            </ul>
          </li>
        </ul>
      <?php } ?>
      

        <!-- dropdown (falls notwendig)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
          -->
        <!-- disabled link-->

        <!--
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
          
      </ul>-->
      <!-- Search form -->
      <!--<form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>-->
    </div>
  </div>
</nav>

    <!-- old version of nav bar, before bootstrap-->

    <!--
    <nav class="nav">
        <div class="nav-left">
            <a href="main_page.html">Hauptseite</a> |
             <a href="news.html">News</a> 
        </div>
        <div class="nav-right">
            <a href="/login.html">Einloggen</a> |
            <a href="/registrierung.html">Registrieren</a>
        </div>
        <div class="nav-right">
            <a href="/login.html"><img class="user-icon" src="user_icon.png" alt="Login Icon" style="max-width:30px" style="text-align:right"/></a>
            &nbsp;
        </div>    
    </nav>
    -->

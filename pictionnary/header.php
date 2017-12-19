<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Pictionnary - Inscription</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>
<header>
    <?php
        session_start();
        //ini_set("xdebug.dump.SESSION", "*");
        //xdebug_dump_superglobals();

        if(isset($_SESSION['prenom'])){
            ?>
            <h1>Bonjour <?php echo $_SESSION["prenom"] . " " . $_SESSION["nom"]; ?></h1><br>
            <form action="logout.php" method="post">
                <input type="submit" value="D&eacute;connexion"/>
            </form>
            <?php
        }
        else{
            ?>
            <form class="inscription" action="req_login.php" method="post">
                <ul>
                    <li><label for="nom">Login : </label><input id="nom" type="text" name="login"/><br></li>
                    <li><label for="mdp1">Mot de passe : </label><input id="mdp1" type="password" name="password"/><br></li>
                    <li><input type="submit" value="Connexion"/></li>
                </ul>
            </form>
            <br><br>
            <a href="page-inscription.php">Pas encore inscrit, cliquez ici</a>
            <?php
        }
    ?>
</header>
</body>
</html>
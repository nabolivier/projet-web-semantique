<?php
session_start();
    $login = stripslashes($_POST["login"]);
    $password = stripslashes($_POST["password"]);

    $dbh = new PDO('mysql:host=localhost;dbname=pictionnary', 'test', 'test');
    $sql = $dbh->query("SELECT * FROM users WHERE email = '" . $login . "'");

if($sql->rowCount()>0){
    $row = $sql->fetch();
    if($row["password"] == $password){
        $_SESSION["email"] = $row["email"];
        $_SESSION["password"] = $row["password"];
        $_SESSION["nom"] = $row["nom"];
        $_SESSION["prenom"] = $row["prenom"];
        $_SESSION["tel"] = $row["tel"];
        $_SESSION["website"] = $row["website"];
        $_SESSION["sexe"] = $row["sexe"];
        $_SESSION["birthdate"] = $row["birthdate"];
        $_SESSION["ville"] = $row["ville"];
        $_SESSION["taille"] = $row["taille"];
        $_SESSION["couleur"] = $row["couleur"];
        $_SESSION["profilepic"] = $row["profilepic"];
        header("Location: main.php");
    }
}
?>
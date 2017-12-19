<?php
// récupérer les éléments du formulaire
// et se protéger contre l'injection MySQL (plus de détails ici: http://us.php.net/mysql_real_escape_string)
$email=stripslashes($_POST['email']);
$password=stripslashes($_POST['password']);
$nom=stripslashes($_POST['nom']);
$prenom=stripslashes($_POST['prenom']);
$tel=stripslashes($_POST['tel']);
$website=stripslashes($_POST['website']);
$sexe='';
if (array_key_exists('sexe',$_POST)) {
    $sexe=stripslashes($_POST['sexe']);
}
$birthdate=stripslashes($_POST['birthdate']);
$ville=stripslashes($_POST['ville']);
$taille=stripslashes($_POST['taille']);
$couleur=stripslashes($_POST['couleur']);
$profilepic=stripslashes($_POST['profilepic']);

try {
    // Connect to server and select database.
    $dbh = new PDO('mysql:host=localhost;dbname=pictionnary', 'test', 'test');

    // Vérifier si un utilisateur avec cette adresse email existe dans la table.
    // En SQL: sélectionner tous les tuples de la table USERS tels que l'email est égal à $email.
    $sql = $dbh->query("SELECT * FROM users WHERE email = '" . $email . "'");

    if ($sql->rowCount()>0) {
        // TODO
        // rediriger l'utilisateur ici, avec tous les paramètres du formulaire plus le message d'erreur
        // utiliser à bon escient la méthode htmlspecialchars http://www.php.net/manual/fr/function.htmlspecialchars.php          // et/ou la méthode urlencode http://php.net/manual/fr/function.urlencode.php
    }
    else {
        // Tenter d'inscrire l'utilisateur dans la base
        $sql = $dbh->prepare("INSERT INTO users (email, password, nom, prenom, tel, website, sexe, birthdate, ville, taille, couleur, profilepic) "
            . "VALUES (:email, :password, :nom, :prenom, :tel, :website, :sexe, :birthdate, :ville, :taille, :couleur, :profilepic)");
        $sql->bindParam(":email", $email);
        $sql->bindParam(":password", $password);
        if($nom == ""){
            $nom = "DEFAULT";
            $sql->bindParam(":nom", $nom);
        }else{
            $sql->bindParam(":nom", $nom);
        }
        $sql->bindParam(":prenom", $prenom);
        if($tel == ""){
            $tel = "DEFAULT";
            $sql->bindParam(":tel", $tel);
        }else{
            $sql->bindParam(":tel", $tel);
        }
        if($website == ""){
            $website = "DEFAULT";
            $sql->bindParam(":website", $website);
        }else{
            $sql->bindParam(":website", $website);
        }
        if($sexe == ""){
            $sexe = "DEFAULT";
            $sql->bindParam(":sexe", $sexe);
        }elseif($sexe == "H" || $sexe == "Homme"){
            $sexe = "H";
            $sql->bindParam(":sexe", $sexe);
        }elseif($sexe == "F" || $sexe == "Femme"){
            $sexe = "F";
            $sql->bindParam(":sexe", $sexe);
        }else{
            echo $sexe;
        }
        $sql->bindParam(":birthdate", $birthdate);
        if($ville == ""){
            $ville = "DEFAULT";
            $sql->bindParam(":ville", $ville);
        }else{
            $sql->bindParam(":ville", $ville);
        }
        $sql->bindParam(":taille", $taille);
        $couleur = substr($couleur, 1);
        $sql->bindParam(":couleur", $couleur);
        $sql->bindParam(":profilepic", $profilepic);
        // de même, lier la valeur pour le mot de passe
        // lier la valeur pour le nom, attention le nom peut être nul, il faut alors lier avec NULL, ou DEFAULT
        // idem pour le prenom, tel, website, birthdate, ville, taille, profilepic
        // n.b., notez: birthdate est au bon format ici, ce serait pas le cas pour un SGBD Oracle par exemple
        // idem pour la couleur, attention au format ici (7 caractères, 6 caractères attendus seulement)
        // idem pour le prenom, tel, website
        // idem pour le sexe, attention il faut être sûr que c'est bien 'H', 'F', ou ''

        // on tente d'exécuter la requête SQL, si la méthode renvoie faux alors une erreur a été rencontrée.
        if (!$sql->execute()) {
            echo "PDO::errorInfo():<br/>";
            $err = $sql->errorInfo();
            print_r($err);
        } else {

            // ici démarrer une session
            session_start();

            // ensuite on requête à nouveau la base pour l'utilisateur qui vient d'être inscrit, et
            $sql = $dbh->query("SELECT u.id, u.email, u.nom, u.prenom, u.couleur, u.profilepic FROM USERS u WHERE u.email='".$email."'");
            if ($sql->rowCount()<1) {
                header("Location: main.php?erreur=".urlencode("Un problème est survenu"));
            }
            else {
                $row = $sql->fetch();
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
            }

            header("Location: main.php");
            exit();
        }
        $dbh = null;
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    $dbh = null;
    die();
}
?>
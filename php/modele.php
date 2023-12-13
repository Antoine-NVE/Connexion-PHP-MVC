<?php
require "connect.php";

// Connexion à la BDD
function connect_db()
{
  $dsn = "mysql:dbname=" . BASE . ";host=" . SERVER;
  try {
    $option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    $connexion = new PDO($dsn, USER, PASSWD, $option);
  } catch (PDOException $e) {
    printf("Echec connexion : %s\n", $e->getMessage());
    exit();
  }

  return $connexion;
}

// Vérifie le mot de passe
function verifier_password($password): bool
{
  $majuscule = preg_match('/[A-Z]/', $password);
  $minuscule = preg_match('/[a-z]/', $password);
  $chiffre = preg_match('/[0-9]/', $password);

  return $majuscule && $minuscule && $chiffre;
}

// Vérifie l'email
function verifier_email($email): bool
{
  return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

// Fonction qui contrôle les données
function controles_donnees($utilisateur, $email, $password, $fonction): array
{
  // Array qui recevra les données
  $donnees = array();

  // Enlève les balises HTML
  $utilisateur = htmlspecialchars(strip_tags($utilisateur));
  $email = htmlspecialchars(strip_tags($email));
  $fonction = htmlspecialchars(strip_tags($fonction));

  // Vérifie que les données sont OK
  if (strlen($utilisateur) > 2 && verifier_email($email)  && verifier_password($password)) {
    // Crypte le mot de passe
    $password = password_hash($password, PASSWORD_BCRYPT);

    // Rempli l'array
    $donnees["utilisateur"] = $utilisateur;
    $donnees["email"] = $email;
    $donnees["password"] = $password;
    $donnees["fonction"] = $fonction;
    $donnees["valides"] = true;
  } else {
    $donnees["valides"] = false;
  }

  return $donnees;
}

// Vérifie que l'utilisateur n'existe pas déjà en base
function controle_doublons($email)
{
  $connexion = connect_db();
  $sql = "SELECT * FROM utilisateurs WHERE email_user = :email";
  $reponse = $connexion->prepare($sql);
  $reponse->bindValue(":email", $email);
  $reponse->execute();

  $doublon = $reponse->fetch();

  return $doublon;
}

// Fonction d'inscription
function inscription($utilisateur, $email, $password, $fonction): string
{
  // Vérifie les données
  $donnees = controles_donnees($utilisateur, $email, $password, $fonction);

  // Si les données sont valides
  if ($donnees["valides"]) {
    // Vérifie que l'utilisateur n'existe pas déjà
    if (!controle_doublons($donnees["email"])) {
      // Insertion en base
      $connexion = connect_db();
      $sql = "INSERT INTO utilisateurs (email_user, login_user, pwd_user, fonction) VALUES (:email, :utilisateur, :password, :fonction)";
      $reponse = $connexion->prepare($sql);
      $reponse->bindValue(":email", $donnees["email"]);
      $reponse->bindValue(":utilisateur", $donnees["utilisateur"]);
      $reponse->bindValue(":password", $donnees["password"]);
      $reponse->bindValue(":fonction", $donnees["fonction"]);
      $reponse->execute();

      // Unset $_POST
      unset($_POST["utilisateur"]);
      unset($_POST["email"]);
      unset($_POST["password"]);
      unset($_POST["fonction"]);
      unset($_POST["valides"]);

      return "Données insérées avec succès";
    } else {
      return "Email déjà existant";
    }
  } else {
    return "Données incorrectes";
  }
}

// Fonction de connexion
function connexion($utilisateur, $email, $password)
{
  // Enlève les balises HTML
  $utilisateur = htmlspecialchars(strip_tags($utilisateur));
  $email = htmlspecialchars(strip_tags($email));

  // Select grâce au nom d'utilisateur et à l'email
  $connexion = connect_db();
  $sql = "SELECT * FROM utilisateurs WHERE email_user = :email AND login_user = :utilisateur";
  $reponse = $connexion->prepare($sql);
  $reponse->bindValue(":email", $email);
  $reponse->bindValue(":utilisateur", $utilisateur);
  $reponse->execute();

  $reponse = $reponse->fetch();

  // Si l'utilisateur existe
  if ($reponse !== false) {
    // Vérifie le mot de passe
    if (password_verify($password, $reponse["pwd_user"])) {
      return "Redirection...";
    } else {
      return "Mot de passe incorrect";
    }
  } else {
    return "Identifiant ou email incorrect";
  }
}

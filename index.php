<?php
require "php/controller.php";

try {
  // S'il n'y a pas d'action
  if (!isset($_GET["action"])) {
    formulaire_connexion();
  }

  // Si l'action est "inscription"
  elseif ($_GET["action"] === "inscription") {
    formulaire_inscription();

    // Si l'action est "bienvenue"
  } elseif ($_GET["action"] === "bienvenue")
    page_accueil();

  // Sinon
  else {
    throw new Exception("Page non trouvée");
  }
} catch (Exception $erreur) {
  echo erreur($erreur->getMessage());
}

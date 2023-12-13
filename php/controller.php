<?php
require "modele.php";

function formulaire_connexion()
{
  require "templates/connexion.php";
}

function formulaire_inscription()
{
  require "templates/inscription.php";
}

function inscription_utilisateur($utilisateur, $email, $password, $fonction)
{
  return inscription($utilisateur, $email, $password, $fonction);
}

function connexion_utilisateur($utilisateur, $email, $password)
{
  return connexion($utilisateur, $email, $password);
}

function page_accueil()
{
  require "templates/accueil.php";
}

function erreur($message)
{
  require "templates/erreur.php";
}

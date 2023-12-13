<?php
require_once "php/controller.php";
if (isset($_POST["utilisateur"])) {
  $message = connexion_utilisateur($_POST["utilisateur"], $_POST["email"], $_POST["password"]);
  if ($message === "Redirection...") {
    header("location:index.php?action=bienvenue");
  }
}
$title = "Connexion";
ob_start();
?>

<h1>Connexion</h1>
<form action="" method="post">
  <a href="index.php?action=inscription" class="right">Créer un compte</a>
  <table>
    <tbody>
      <tr>
        <td><label for="utilisateur">Utilisateur : </label></td>
        <td><input type="text" id="utilisateur" name="utilisateur" value="<?php echo (isset($_POST["utilisateur"]) ? $_POST["utilisateur"] : "") ?>"></td>
        <td class="erreurs"></td>
      </tr>
      <tr>
        <td><label for="email">Email : </label></td>
        <td><input type="text" id="email" name="email" value="<?php echo (isset($_POST["email"]) ? $_POST["email"] : "") ?>"></td>
        <td class="erreurs"></td>
      </tr>
      <tr>
        <td><label for="password">Mot de passe : </label></td>
        <td><input type="password" id="password" name="password" value="<?php echo (isset($_POST["password"]) ? $_POST["password"] : "") ?>"></td>
        <td class="erreurs"></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <button type="submit">Valider</button>
          <button type="reset">Annuler</button>
        </td>
        <td class="<?php echo (isset($message) ? ($message === "Redirection..." ? "succes" : "erreurs") : "succes") ?>"><?php echo (isset($message) ? $message : "") ?></td>
      </tr>
    </tbody>
  </table>
</form>

<?php
$content = ob_get_clean();
include "baselayout.php";
?>
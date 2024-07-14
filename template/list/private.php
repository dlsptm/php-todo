<?php
   require_once './template/base.php';
   if (isset($_SESSION['granted'][$param])) {
    header("Location: index.php?t=todo&a=view&p=$param");
    exit;
}
?>

<form action="" method="post">
  <label for="password">Mot de passe</label>
  <input type="password" name="password" id="password">
  <button>Valider</button>
</form>
<?php
   require_once './template/base.php';

?>

<main>
  <h2>Enregistrement</h2>
  <form action="" method="post">
    <label>
      <span>Pseudo :</span>
      <input type="text" name="username">
    </label>
    <label>
      <span>Email :</span>
      <input type="text" name="email">
    </label>
    <label>
      <span>Mot de passe :</span>
      <input type="password" name="password">
    </label>
    <button>Valider</button>
  </form>
</main>
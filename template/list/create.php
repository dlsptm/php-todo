<?php
   require_once './template/base.php';

?>

<h2>Ajouter une liste</h2>
        <form action="" method="post">
            <label>
                <span>Titre de la liste : </span>
                <input type="text" name="title">
            </label>
            <label>
                <span>Mot de passe * : </span>
                <input type="password" name="password">
            </label>
            <button>Valider</button>
            <small>* : facultatif</small>
        </form>

        <a href="index.php?t=list&a=view&page=1" class="btn">Voir les listes</a>
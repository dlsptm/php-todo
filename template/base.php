<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="./asset/style.css">
    <script src="./asset/script.js" defer></script>
    
    <title>To Do List</title>
</head>
<body>
    <header>
        <nav>
            <ul>
              <?php
                  if (!isset($_SESSION['user'])):
                    ?>
                <li><a href="index.php?t=user&a=login">Se connecter</a></li>
                <li><a href="index.php?t=user&a=signin">S'enregistrer</a></li>
                <?php
                  else :
                    ?>
                <li><a href="index.php?t=list&a=create">Ajouter une liste</a></li>
                <li><a href="index.php?t=list&a=view&page=1">Voir les listes</a></li>
                <li><a href="index.php?t=user&a=logout">Deconnexion</a></li>
                <?php
                  endif;
                ?>
            </ul>
        </nav>
    </header>
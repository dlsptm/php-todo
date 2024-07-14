<?php
    require_once './util/util.php';
    // on retire le trailing slash éventuel de l'url
    // on récupère l'url
    $uri = $_SERVER['REQUEST_URI'];

    // on vérifie que uri n'est pas vide et se termine par un /

    if (!empty($uri) && $uri[-1] === '/') {
      // on enlève le /
      $uri = substr($uri, 0, -1);

      // on envoie un code de redirection permanente
      http_response_code(301);

      // on redirige vers l'url /
      header('Location:' . $uri);
    }

    $uri = explode('/', $uri);
    $current_page = $uri[2];
    // on gère les paramètres d'URL
    //p=controlleur/methode/paramètres
    // on sépare les paramètres dans un tableau
    if (isset($_GET['t'], $_GET['a']) && str_starts_with($current_page, 'index.php')) {
        // on a au moins 1 paramètre
        // on reccupère le nom du controller afin de l'instancier
        // array_shift($params) = enlève la prmeière valeur du tableau
        $page = './template/'.$_GET['t'].'/' .$_GET['a'] . '.php';

        if ($_GET['t'] == 'user') {
        $controller = './security/'.$_GET['a'].'.php';
        } else {
          $controller = './controllers/'.$_GET['t'].'Controller.php';
        }
        
        if (!file_exists($page) || !file_exists($controller)) {
          http_response_code(404);
          header('Location: ./_404.php');
        } else {
          require_once $page;
          require_once $controller;
        }

      } else {
        http_response_code(404);
        header('Location: ./_404.php');
      }
    
<?php
require_once './util/util.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php?t=user&a=login');
    echo '<div class="alert-danger">L\'email ou le mot de passe est incorrect</div>';
    exit;
}

$password = !empty($_POST['password']) ? htmlspecialchars(strip_tags(trim($_POST['password']))) : '';

if (isset($_POST['title'], $_POST['password'])) {
  $title = htmlspecialchars(strip_tags(trim($_POST['title'])));
  $password = !empty($password)  ? password_hash($password, PASSWORD_DEFAULT) : $password;
  
    $lists = [
        'title' => $title,
        'password' => $password,
        'user' => $userId,
        'todo' => array()
    ];

    $content['lists'][] = $lists;
    file_put_contents('text.txt', json_encode($content));
    header("Location: index.php?t=list&a=view&page=1");
    exit;
}

//GRANTED
if (
    $param !== null && 
    isset($content['lists'][$param], $_POST['password']) && 
    password_verify($password, $content['lists'][$param]['password']) ||
    $param !== null && 
    isset($content['lists'][$param]) && 
    empty($content['lists'][$param]['password'])
    ) {
      
    $_SESSION['granted'][$param] = true;
    header("Location: index.php?t=todo&a=view&p=$param");
    exit;
}



// Redirect if invalid param
if ($param !== null && !isset($content['lists'][$param])) {
    header("Location: index.php?t=list&a=create");
    exit;
}


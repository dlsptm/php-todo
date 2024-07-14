<?php
require_once './util/util.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php?t=user&a=login');
    echo '<div class="alert-danger">L\'email ou le mot de passe est incorrect</div>';
    exit;
}

if (isset($_SESSION['user']) && $content['lists'][$param]['user'] !== $_SESSION['user']['id']) {
    header("Location: index.php?t=list&a=view&page=1");
    exit;
};

//INSERT
if (isset($_POST["start"], $_POST["deadline"])) { 
  // LOCAL START AND DEADLINE
  $start = trim($_POST['start']);
  $start = explode("T", $start);
  $startLocalDate = explode('-', $start[0]);
  $startLocalDate = $startLocalDate[2].'-'.$startLocalDate[1].'-'.$startLocalDate[0];
  $start = $startLocalDate.' '.$start[1];

  $deadline = trim($_POST['deadline']);
  $deadline = explode("T", $deadline);
  $deadlineLocalDate = explode('-', $deadline[0]);
  $deadlineLocalDate = $deadlineLocalDate[2].'-'.$deadlineLocalDate[1].'-'.$deadlineLocalDate[0];
  $deadline = $deadlineLocalDate.' '.$deadline[1];
}

$date = isset($_POST['completed']) ? date('d-m-Y H:i') : '';

if (isset($_GET['a'], $_POST["title"], $_POST["description"], $_POST["type"], $_POST['start']) && 
  !empty($_POST["title"]) && 
  !empty($_POST["description"]) && 
  !empty($_POST["type"]) && 
  !empty($_POST["start"] && 
  $_GET['a'] !== 'edit' && 
  $_GET['a'] !== 'delete')) {
  
  $title = trim($_POST["title"]);
  $description = trim($_POST['description']);
  $type = trim($_POST['type']);
  $option = trim($_POST['option']);

  $content['lists'][$param]['todo'][] = [
      'title' => $title,
      'description' => $description,
      'type' => $type,
      'start' => $start,
      'deadline' => $deadline,
      'option' => $option,
      'completed' => $date
  ];

  usort($content['lists'][$param]['todo'], function($a, $b) {
     
      $option =  $b['option'] <=> $a['option'];

      // tri d'abord par option (ongoing or finished)
      if ($option !== 0) {
          return $option;
      }

      // si ongoing, on tri par type (urgent, normal, faible)
      if ($a['option'] == 'ongoing') {
          return $b['type'] <=> $a['type'];
      }

      // si les options sont les mêmes mais pas ongoing donc terminées
      return 0;
  });
  
  file_put_contents('text.txt', json_encode($content));

}

// UPDATE & DELETE
if (isset($_GET['t'], $_GET['a'], $_GET['id']) && !empty($_GET['t']) && !empty($_GET['a']) && !empty($_GET['id'])) {
    if ($_GET['t'] == 'todo' && $_GET['a'] == 'edit' && isset($_POST['title'], $_POST['description'], $_POST['type'], $_POST['option'])) {
        
        $content['lists'][$param]['todo'][$id] = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'type' => $_POST['type'],
            'start' => $start,
            'deadline' => $deadline,
            'option' => $_POST['option'],
            'completed' => $date,
        ];
        
        file_put_contents('text.txt', json_encode($content));

    } elseif (isset($_GET['t'], $_GET['a'], $_GET['id']) && $_GET['t'] == 'todo' && $_GET['a'] == 'delete') {
        unset($content['lists'][$param]['todo'][$id]);
        file_put_contents('text.txt', json_encode($content));
        header("Location: index.php?t=todo&a=view&p=$param");
        exit;
    }
}


if ($_POST) {
    header("Location: index.php?t=todo&a=view&p=$param");
    exit;
}

if (isset($_GET['p'], $_GET['id'], $content['lists'][$param]['todo'][$id])) {
  $currentTitle = isset($_GET['p'], $_GET['id'], $content['lists'][$param]['todo'][$id]['title']) ? $content['lists'][$param]['todo'][$id]['title'] : '';
  
  $currentDescription = isset($_GET['p'], $_GET['id'], $content['lists'][$param]['todo'][$id]['description']) ? $content['lists'][$param]['todo'][$id]['description'] : '';
  
  $currentStart = isset($_GET['p'], $_GET['id'], $content['lists'][$param]['todo'][$id]['start']) ? $content['lists'][$param]['todo'][$id]['start'] : '';
  
  $currentDeadline = isset($_GET['p'], $_GET['id'], $content['lists'][$param]['todo'][$id]['deadline']) ? $content['lists'][$param]['todo'][$id]['deadline'] : '';

  $currentCompletedDate = isset($_GET['p'], $_GET['id'], $content['lists'][$param]['todo'][$id]['completed']) ? $content['lists'][$param]['todo'][$id]['completed'] : '';

  $currentDeadline = explode(' ', $currentDeadline);
  $currentDeadlineP = explode('-', $currentDeadline[0]);
  $currentDeadline = $currentDeadlineP ?$currentDeadlineP[2].'-'.$currentDeadlineP[1].'-'.$currentDeadlineP[0].'T'.$currentDeadline[1] : '';


  $currentStart = explode(' ', $currentStart);
  $currentStartP = explode('-', $currentStart[0]);
  $currentStart = $currentStartP[2].'-'.$currentStartP[1].'-'.$currentStartP[0].'T'.$currentStart[1];
}



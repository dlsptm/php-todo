<?php
session_start();

$content = file_get_contents('text.txt');
$content = json_decode($content, true);

function dump(mixed $data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

// LISTE
$param = isset($_GET['p']) ? $_GET['p']  : null;
$id = isset($_GET['id']) ? $_GET['id'] - 1 : null;
$currentDate = new DateTime();
$currentDate = $currentDate->setTimezone(new DateTimeZone('Europe/Paris'))->format("d-m-Y H:i");


$mdp = !empty($_POST['listMdp']) ? password_hash(htmlspecialchars(strip_tags(trim($_POST['listMdp']))), PASSWORD_DEFAULT) : '';

if (isset($_POST['listTitle'], $_POST['listMdp'])) {
    $title = htmlspecialchars(strip_tags(trim($_POST['listTitle'])));

    $lists = [
        'title' => $title,
        'mdp' => $mdp,
        'todo' => array()
    ];

    $content[] = $lists;
    file_put_contents('text.txt', json_encode($content));
}

// granted
$password = isset($_POST['password']) ? htmlspecialchars(strip_tags(trim($_POST['password']))) :'';

if (
    $param !== null && 
    isset($content[$param]) && 
    isset($_POST['password']) && 
    password_verify($password, $content[$param]['mdp']) ||
    $param !== null && 
    isset($content[$param]) && 
    empty($content[$param]['mdp'])
    ) {
    $_SESSION['granted'][$param] = true;
    header("Location: index.php?p=$param");
    exit;
}

// Redirect if invalid param
if ($param !== null && !isset($content[$param])) {
    header("Location: index.php");
    exit;
}

// TO DO - INSERT
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

if (isset($_GET['p'], $_POST["title"], $_POST["description"], $_POST["type"], $_POST['start']) && 
    !empty($_POST["title"]) && 
    !empty($_POST["description"]) && 
    !empty($_POST["type"]) && 
    !empty($_POST["start"]) && 
    !isset($_GET['a'])) {
    $title = trim($_POST["title"]);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);
    $option = trim($_POST['option']);

    $content[$param]['todo'][] = [
        'title' => $title,
        'description' => $description,
        'type' => $type,
        'start' => $start,
        'deadline' => $deadline,
        'option' => $option,
        'completed' => $date
    ];

    usort($content[$param]['todo'], function($a, $b) {
       
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

// TO DO - UPDATE & DELETE
if (isset($_GET['a'], $_GET['id']) && !empty($_GET['a']) && !empty($_GET['id'])) {
    if ($_GET['a'] == 'edit' && isset($_POST['title'], $_POST['description'], $_POST['type'])) {
        
        $content[$param]['todo'][$id] = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'type' => $_POST['type'],
            'start' => $start,
            'deadline' => $deadline,
            'option' => $_POST['option'],
            'completed' => $date,
        ];
        
        file_put_contents('text.txt', json_encode($content));
    } elseif ($_GET['a'] == 'delete') {
        unset($content[$param]['todo'][$id]);
        file_put_contents('text.txt', json_encode($content));
    }
}


if (!isset($_SESSION['granted'])) {
    $_SESSION['granted'] = [];
}

if ($_POST || (isset($_GET['a']) && $_GET['a'] == 'delete')) {
    $current = json_encode($content);
    file_put_contents('text.txt', $current);
}

if (!empty($_POST) && isset($param) || !empty($_POST) && isset($param, $_GET['a'])) {
    header("Location: index.php?p=$param");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    
    <title>To Do List</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ajouter une liste</a></li>
                <li><a href="index.php?page=1">Voir les listes</a></li>
            </ul>
        </nav>
    </header>
    <main>
    <!-- AJOUT LISTE -->
    <?php if (empty($_GET)): ?>
        <h2>Ajouter une liste</h2>
        <form action="" method="post">
            <label>
                <span>Titre de la liste : </span>
                <input type="text" name="listTitle">
            </label>
            <label>
                <span>Mot de passe * : </span>
                <input type="text" name="listMdp">
            </label>
            <button>Valider</button>
            <small>* : facultatif</small>
        </form>

        <a href="index.php?page=1" class="btn">Voir les liste</a>

        <?php
        endif;
          if (isset($_GET['page'])) :
        ?>
        <h2>Liste</h2>
        <ul>
            <!-- AFFICHAGE DES LISTES -->
            <?php if (isset($content)):
                $nbList = count($content);
                $perPage = 7;
                $pages = ceil($nbList / $perPage);
                
                $currentPage = $_GET['page']; ?>


                <?php 
                for ($i = $perPage * ($_GET['page'] - 1); $i < ($perPage * $_GET['page']); $i++) : 
                    if (isset($content[$i])) :?>
                    <li class="round"><a href="index.php?p=<?= $i; ?>"><?= $content[$i]['title']; ?></a> <?= $content[$i]['mdp'] !== '' ? '🔒 ' : '' ; ?> </li>
                <?php  
               endif;  endfor; ?>

               <ul class="pages">
               <li class="page"><a href="index.php<?= $_GET['page'] != 1 ? '?page='.$_GET['page'] - 1 : '' ?>">← Page précédente</a></li>
               <?php if ($_GET['page'] != $pages) : ?>
                   <li class="page"><a href="index.php?page=<?= $_GET['page'] + 1 ?>">Page suivante →</a></li>
                   <?php endif; ?>
           </ul>
             
          <?php endif; ?>
        </ul>
    <?php endif; 
     ?> 

    <!-- PRIVATE LISTE -->
    <?php 
    if (
        isset($param, $content[$param]['mdp']) && 
        $content[$param]['mdp'] != '' && 
        (!isset($_SESSION['granted'][$param]) || 
        $_SESSION['granted'][$param] != true)): ?>
        <form action="" method="post">
            <label for="password">Mot de passe</label>
            <input type="text" name="password" id="password">
            <button>Valider</button>
        </form>
    <?php endif; ?>

    <!-- ACCES AUX LISTES -->
         <!-- FORM -->
    <?php if (isset($param, $_SESSION['granted'][$param]) && $_SESSION['granted'][$param] == true ||
    isset($content) && array_key_exists($param, $content) && $content[$param]['mdp'] == '') : ?>
        <a href="index.php">← Revenir à la page</a>
        <h2><?= $content[$param]['title']; ?></h2>

        <?php
        if (isset($_GET['p'], $_GET['id'], $content[$param]['todo'][$id])) {
          $currentTitle = isset($_GET['p'], $_GET['id'], $content[$param]['todo'][$id]['title']) ? $content[$param]['todo'][$id]['title'] : '';
          
          $currentDescription = isset($_GET['p'], $_GET['id'], $content[$param]['todo'][$id]['description']) ? $content[$param]['todo'][$id]['description'] : '';
          
          $currentStart = isset($_GET['p'], $_GET['id'], $content[$param]['todo'][$id]['start']) ? $content[$param]['todo'][$id]['start'] : '';
          
          $currentDeadline = isset($_GET['p'], $_GET['id'], $content[$param]['todo'][$id]['deadline']) ? $content[$param]['todo'][$id]['deadline'] : '';

          $currentCompletedDate = isset($_GET['p'], $_GET['id'], $content[$param]['todo'][$id]['completed']) ? $content[$param]['todo'][$id]['completed'] : '';

          $currentDeadline = explode(' ', $currentDeadline);
          $currentDeadlineP = explode('-', $currentDeadline[0]);
          $currentDeadline = $currentDeadlineP ?$currentDeadlineP[2].'-'.$currentDeadlineP[1].'-'.$currentDeadlineP[0].'T'.$currentDeadline[1] : '';


          $currentStart = explode(' ', $currentStart);
          $currentStartP = explode('-', $currentStart[0]);
          $currentStart = $currentStartP[2].'-'.$currentStartP[1].'-'.$currentStartP[0].'T'.$currentStart[1];
        }
          ?>

        <form action="" method="post">
            <label>
                <span>Titre : </span>
                <input type="text" name="title" 
                    value="<?= isset($currentTitle) ? $currentTitle : ''; ?>" required>
            </label>
            <label>
                <span>Description : </span>
                <input type="textarea" name="description" 
                    value="<?= isset($edcurrentDescription) ? $edcurrentDescription : ''; ?>" required>
            </label>
            <label>
                <span>Date de début : </span>
                <input type="datetime-local" name="start" value="<?= isset($currentStart) ? $currentStart : '' ; ?>" required>
            </label>
            <label>
                <span>Date butoir : </span>
                <input type="datetime-local" name="deadline" value="<?= $currentDeadline ; ?>" required>
            </label>
            <label>
                <span>Type : </span>
                <select name="type">
                    <option value="normal" selected>Normal</option>
                    <option value="urgent">Urgent</option>
                    <option value="faible">Faible</option>
                </select>
            </label>
            <label>
                <span>Option : </span>
                <select name="option" id='option'>
                    <option value="todo">A faire</option>
                    <option value="ongoing">En cours</option>
                    <option value="finished">Terminer</option>
                </select>
            </label>
            <input type="datetime-local" name="completed" id="completed">

            <button>Valider</button>
        </form>

            <!-- TABLE -->
        <?php if (array_key_exists($param, $content)): 

        $behind = 0;
        $finished = 0;
        $ongoing = 0;

        foreach ($content[$param]['todo'] as $index => $value) {
            if ($currentDate > $value['deadline'] && $value['option'] !== 'finished') {
                $behind++;
            }             
            if ($value['option'] == 'ongoing'){
                $ongoing++ ;
            } else {
                $finished++;
            }

        }


        if (count($content[$param]['todo']) > 0):
            if ($finished > 0) :
            ?>

        
        <a href="index.php?p=<?= $param ; ?>&f=true" target="_blank">Voir les tâches terminées</a> <?php endif; ?>
        <ul class="count">
            <li>Total =  <?= count($content[$param]['todo']) ; ?></li>
            <li>Tâches en cours = <?= $ongoing ; ?> dont <span id="late"><?= $behind ; ?> </span>en retard</li>
            <li>Taches terminées = <?= $finished ; ?>
            </li>
        </ul>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Date de commencement</th>
                            <th>Date Butoir</th>
                            <th>Option</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($content[$param]['todo'] as $key => $value):
                            if ($value['option'] == 'ongoing' && !isset($_GET['f'])) :
                            ?>
                            <tr>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late"' : "" ?>><?= $key + 1; ?></td>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late"' : "" ?>><?=$value['title']; ?></td>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late"' : "" ?>><?= $value['description']; ?></td>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late"' : "" ?>><?= $value['type']; ?></td>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late"' : "" ?>><?= $value['start']; ?></td>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late"' : "" ?>><?= $value['deadline']; ?></td>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late"' : "" ?>><?= $value['option']; ?></td>
                                <td <?= $currentDate > $value['deadline'] && $value['option'] !== 'finished' ? 'class="late btns"' : "class='btns'" ?>>
                                    <a href="index.php?p=<?= $param; ?>&a=edit&id=<?= $key + 1; ?>" class="btn">Modifier</a>
                                    <a href="index.php?p=<?= $param; ?>&a=delete&id=<?= $key + 1; ?>" class="btn">Supprimer</a></td>
                            </tr>
                        <?php elseif ($value['option'] !== 'ongoing' && isset($_GET['f'])) :?>
                            <tr>
                                <td><?= $key + 1; ?></td>
                                <td><?=$value['title']; ?></td>
                                <td><?= $value['description']; ?></td>
                                <td><?= $value['type']; ?></td>
                                <td><?= $value['start']; ?></td>
                                <td><?= $value['deadline']; ?></td>
                                <td><?= $value['option']; ?></td>
                                <td class="btns">
                                    <a href="index.php?p=<?= $param; ?>&a=edit&id=<?= $key + 1; ?>" class="btn">Modifier</a>
                                    <a href="index.php?p=<?= $param; ?>&a=delete&id=<?= $key + 1; ?>" class="btn">Supprimer</a></td>
                            </tr>
                            <?php endif; endforeach; ?>
                    </tbody>
                </table>
            <?php endif;
     endif; 
    endif; ?>
    </main>
</body>
</html>
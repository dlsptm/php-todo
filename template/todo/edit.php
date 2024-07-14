 <?php 
 require_once './template/base.php';


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
    ?>

<h2>Modifier une tâche</h2>
<form action="" method="post">
            <label>
                <span>Titre : </span>
                <input type="text" name="title" 
                    value="<?= isset($currentTitle) ? $currentTitle : ''; ?>" required>
            </label>
            <label>
                <span>Description : </span>
                <input type="textarea" name="description" 
                    value="<?= isset($currentDescription) ? $currentDescription : ''; ?>" required>
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
                    <option value="ongoing">En cours</option>
                    <option value="finished">Terminer</option>
                </select>
            </label>
            <input type="datetime-local" name="completed" id="completed">

            <button>Valider</button>
        </form>
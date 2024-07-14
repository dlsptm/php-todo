<?php require_once './template/base.php'; ?>

<h2>Liste des tâches de <?= $content['lists'][$param]['title'] ; ?></h2>

<?php
if (array_key_exists($param, $content['lists'])): 
    $behind = 0;
    $finished = 0;
    $ongoing = 0;

    foreach ($content['lists'][$param]['todo'] as $index => $value) {
        if ($currentDate > $value['deadline'] && $value['option'] !== 'finished') {
            $behind++;
        }             
        if ($value['option'] == 'ongoing'){
            $ongoing++ ;
        } else {
            $finished++;
        }

    }


    if (count($content['lists'][$param]['todo']) > 0):
        if ($finished > 0) :
        ?>


    <a href="index.php?p=<?= $param ; ?>&f=true" target="_blank">Voir les tâches terminées</a> <?php endif; ?>
    <ul class="count">
        <li>Total =  <?= count($content['lists'][$param]['todo']) ; ?></li>
        <li>Tâches en cours = <?= $ongoing ; ?> dont <span id="late"><?= $behind ; ?> </span>en retard</li>
        <li>Taches terminées = <?= $finished ; ?>
        </li>
    </ul>
    <a href="index.php?t=todo&a=create&p=<?= $param ; ?>" class="btn todo">Ajouter une tâche</a>

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
                    <?php foreach ($content['lists'][$param]['todo'] as $key => $value):
                        if ($value['option'] == 'ongoing') :
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
                                <a href="index.php?t=todo&a=edit&p=<?= $param; ?>&id=<?= $key + 1; ?>" class="btn">Modifier</a>
                                <a href="index.php?t=todo&a=delete&p=<?= $param; ?>&id=<?= $key + 1; ?>" class="btn">Supprimer</a></td>
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
                                <a href="index.php?t=todo&a=edit&p=<?= $param; ?>&id=<?= $key + 1; ?>" class="btn">Modifier</a>
                                <a href="index.php?t=todo&a=delete&p=<?= $param; ?>&a=delete&id=<?= $key + 1; ?>" class="btn">Supprimer</a></td>
                        </tr>
                        <?php endif; endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
        
            <a href="index.php?t=todo&a=create&p=<?= $param ; ?>" class="btn">Ajouter une tâche</a>
    <?php endif; endif; 
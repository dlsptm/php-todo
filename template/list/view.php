<?php
   require_once './template/base.php';
?>

<h2>Liste</h2>
        <ul>
            <!-- AFFICHAGE DES LISTES -->
            <?php if (isset($content['lists']) && count($content['lists']) > 0):
                $nbList = count($content['lists']);
                $perPage = 7;
                $pages = ceil($nbList / $perPage);
                
                $currentPage = intval($_GET['page']); ?>
                


                <?php 
                for ($i = $perPage * ($currentPage - 1); $i < ($perPage * $currentPage); $i++) : 
                    if (isset($content['lists'][$i]) && $content['lists'][$i]['user'] == $userId ) :?>

                    <li class="round">
                        <?php
                          if ($content['lists'][$i]['password'] !== '') :
                        ?>
                        <a href="index.php?t=list&a=private&p=<?= $i; ?>"><?= $content['lists'][$i]['title']; ?> 🔒</a>  
                        <?php
                          else :
                        ?>
                        <a href="index.php?t=todo&a=view&p=<?= $i; ?>"><?= $content['lists'][$i]['title']; ?></a>  
                        <?php
                          endif
                        ?>
                    </li>
                <?php  
               endif;  
               endfor; ?>

               <ul class="pages">
               <li class="page"><a href="index.php<?= $_GET['page'] != 1 ? '?t=list&a=view&page='.$_GET['page'] - 1 : '?t=list&a=create' ?>">← Page précédente</a></li>
               <?php if ($_GET['page'] != $pages) : ?>
                   <li class="page"><a href="index.php?t=list&a=view&page=<?= $_GET['page'] + 1 ?>">Page suivante →</a></li>
                   <?php endif; ?>
           </ul>
             
          <?php else : ?>
            <a href="index.php?t=list&a=create" class="btn">Ajouter une liste</a></li>

         <?php endif; ?>
        </ul>
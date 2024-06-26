<?php
    /**
     * Ce template affiche un titre d'article, ses commentaires ainsi que l'audience de chaque articles.
     * 
     */
    ?>
<h2>Audience des articles</h2>
<div class="adminArticle">
    <?php 
        $flag=0;
        foreach ($articles as $article) { 
            if($flag == 0){
    ?>
                <div class="articleLine">
                    <div class="title">
                        <a href="index.php?action=showAudience&ordercolumn=title&ordertype=<?= $column ==='title' && $type === 'ASC' ?'DESC' : 'ASC' ?>">
                            Titres des articles <?=($column === 'title' && $type === 'ASC') ? "  ↓" : (($column === 'title' && $type === 'DESC') ? " ↑" : "")?>
                        </a>
                    </div>
                    <div class="count_view">
                        <a href="index.php?action=showAudience&ordercolumn=count_view&ordertype=<?=$column ==='count_view' && $type === 'ASC' ?'DESC' : 'ASC' ?>">
                            Nombre de vues <?=($column === 'count_view' && $type === 'ASC') ? "  ↓" : (($column === 'count_view' && $type === 'DESC') ? " ↑" : "")?>
                        
                        </a>
                    </div>
                    <div class="count_comment">
                        <a href="index.php?action=showAudience&ordercolumn=count_comment&ordertype=<?=$column ==='count_comment' && $type === 'ASC' ?'DESC' : 'ASC' ?>">
                            Nombre de commentaires <?=($column === 'count_comment' && $type === 'ASC') ? "  ↓" : (($column === 'count_comment' && $type === 'DESC') ? " ↑" : "")?>
                        </a>
                    </div>
                    <div class="date_creation">
                        <a href="index.php?action=showAudience&ordercolumn=date_creation&ordertype=<?=$column ==='date_creation' && $type === 'ASC' ?'DESC' : 'ASC' ?>">
                            Date de publication <?=($column === 'date_creation' && $type === 'ASC') ? "  ↓" : (($column === 'date_creation' && $type === 'DESC') ? " ↑" : "")?>
                        </a>
                    </div>
                </div>
    <?      }
    ?>
        <div class="articleLine flag<?=($flag%2)?>" >
            <div class = "title">
                <p><?= $article->getTitle() ?></p>
            </div>
            <div class = "count_view">
                <p><?=$article->getCountView() ?></p>
            </div>
            <div class = "count_comment">
                <p><?= $article->getCountComments() ?></p>
            </div>
            <div class="date_creation">
                <p><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?> </p>
            </div>
        </div>
    <?php 
        $flag++;
        } ?>
</div>
<div>
    <a class="submit" href="index.php?action=admin">Retour</a>
</div>
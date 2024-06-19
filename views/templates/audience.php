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
                    <div class="title"><a href="#">Titres des articles</a></div>
                    <div class="viewNumber"><a href="#">Nombre de vues</a></div>
                    <div class="commentNumber"><a href="#">Nombre de commentaires</a></div>
                    <div class="publicationDate"><a href="#">Date de publication</a></div>
                </div>
    <?      }
    ?>
        <div class="articleLine flag<?=($flag%2)?>" >
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="viewNumber"><?=$article->getCountView() ?></div>
            <div class="commentNumber"><?= "A traiter" ?></div>
            <div class="publicationDate"><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?></div>
        </div>
    <?php 
        $flag++;
        } ?>
</div>
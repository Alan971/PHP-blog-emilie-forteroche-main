<?php 
    /** 
     * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun. 
     * Et un formulaire pour ajouter un article. 
     */
?>

<h2>Edition des articles</h2>

<div class="adminArticle">
    <?php 
    $flag = 0;
    foreach ($articles as $article) { 
        
        ?>
        <div class="articleLine flag<?=($flag%2)?>">
            <div class="title">
                <a href="index.php?action=supprComment&titleArticle=<?= $article->getTitle() ?>" title="Cliquez ici pour gérer les commentaires">
                    <?= $article->getTitle() ?>
                </a>
            </div>
            <div class="content"><?= $article->getContent(200) ?></div>
            <div><a class="submit" href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">Modifier</a></div>
            <div><a class="submit" href="index.php?action=deleteArticle&id=<?= $article->getId() ?>" 
                <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?> >Supprimer</a></div>
        </div>
    <?php
        $flag++; 
    } ?>
</div>

<div>
    <a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>
    <a class="submit" href="index.php?action=showAudience">Audience des articles</a>
</div>


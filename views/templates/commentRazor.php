<?php
    /**
     * Ce template affiche un titre d'article et ses commentaires.
     * Il affiche permet de supprimer d'un d'entre eux
     */
?>


    <h2>Suppression d'un commentaire de l'article : </h2>
    
    <form name="deledeComment" method="POST" action="index.php?action=deleteComment">
    <div class = "titleAndDate">
        <span id="litleTitle"><?=Utils::format($selectedArticle->getTitle())?></span>
        <p class="info"> Publié le <?= Utils::convertDateToFrenchFormat($selectedArticle->getDateCreation()) ?></p>
        <?php if ($selectedArticle->getDateUpdate() != null && $selectedArticle->getDateUpdate()!= $selectedArticle->getDateCreation()) { ?>
        <p class="info"> Modifié le <?= Utils::convertDateToFrenchFormat($selectedArticle->getDateUpdate()) ?></p>
        <?php } ?>
    </div>
    
    <div class="commentRazor" id="labelList">
        <select name="commentslist" id="commentslist">
            <option value="-1"></option>
            <?foreach($comments as $comment)
            {?>
                <option value="<?=$comment->getId()?>"><?=$comment->getPseudo() . ", " . Utils::convertDateToFrenchFormat($comment->getDateCreation())?></option>
            <?}?>
        </select>
        <div class="revealedContent">
            <?foreach($comments as $comment)
            {
                echo "<p hidden id='" . $comment->getId() . "'>" . $comment->getContent() . "</p>";
            }?>
        </div>
        <div class="test">
            test
        </div>
        <div class = "buttonChoice">
            <input type="hidden" name="action" value="deleteComment">
            <input class="submit" type="submit" value = "Supprimer" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?") ?>>
            <a class="submit" href="index.php?action=admin">Retour</a>
        </div>
    </div>
    </form>



<?php
    /**
     * Ce template affiche un titre d'article et ses commentaires.
     * Il affiche permet de supprimer d'un d'entre eux
     */
?>
<h2>Gestion des commentaires de l'article : </h2>

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
        <option value="-1">Selectionnez un commentaire</option>
        <?foreach($comments as $comment)
        {?>
            <option value="<?=$comment->getId()?>"><?=$comment->getPseudo() . ", " . Utils::convertDateToFrenchFormat($comment->getDateCreation())?></option>
        <?}?>
    </select>
    <div class = "revealedContent" id = "revealedContent">
        <? $i = 1;
        foreach($comments as $comment)
        {
            echo "<p hidden id='" . $i . "'>" . $comment->getContent() . "</p>";
            $i++;
        }
        ?>
    </div>
    <div class = "buttonChoice">
        <input type="hidden" name="action" value="deleteComment">
        <input class="submit" type="submit" value = "Supprimer" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?") ?>>
        <a class="submit" href="index.php?action=admin">Retour</a>
    </div>
</div>
</form>

<script>
    const selecteur = document.getElementById("commentslist");
    selecteur.addEventListener("change", function() {
        var index = selecteur.selectedIndex;
        div = document.getElementById("revealedContent");
        var allP = div.querySelectorAll('p');
        for (let j=1; j<=allP.length; j++)
        {
            document.getElementById(j).style.display = 'none';
        }
        document.getElementById(index).style.display = 'block';
    });
</script>



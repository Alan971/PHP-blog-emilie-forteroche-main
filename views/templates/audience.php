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
                        <a href="index.php?action=showAudience&previousType=<?=$column?>&previousUpOrDown=<?=$upOrDown?>&type=title&upOrDown=<?=$upOrDown?>">
                            Titres des articles <?if($column == 'title'){echo "  " . $upOrDown;}?>
                        </a>
                    </div>
                    <div class="viewNumber">
                        <a href="index.php?action=showAudience&previousType=<?=$column?>&previousUpOrDown=<?=$upOrDown?>&type=viewNumber&upOrDown=<?=$upOrDown?>">
                            Nombre de vues <?if($column == 'viewNumber'){echo "  " . $upOrDown;}?>
                        
                        </a>
                    </div>
                    <div class="commentNumber">
                        <a href="index.php?action=showAudience&previousType=<?=$column?>&previousUpOrDown=<?=$upOrDown?>&type=commentNumber&upOrDown=<?=$upOrDown?>">
                            Nombre de commentaires <?if($column == 'commentNumber'){echo "  " . $upOrDown;}?>
                        </a>
                    </div>
                    <div class="publicationDate">
                        <a href="index.php?action=showAudience&previousType=<?=$column?>&previousUpOrDown=<?=$upOrDown?>&type=publicationDate&upOrDown=<?=$upOrDown?>">
                            Date de publication <?if($column == 'publicationDate'){echo "  " . $upOrDown;}?>

                        </a>
                    </div>
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
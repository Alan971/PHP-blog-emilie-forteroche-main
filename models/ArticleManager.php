<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager 
{
    /**
     * Récupère tous les articles dans la base.
     * @return array : un tableau d'objets Article.
     * @param Order : utilise l'objet s'il existe pour trier les données de la baee
     */
    public function getAllArticles(?Order $orderBy = null) : array
    {
       $sql = "SELECT 
                    a.id,
                    a.title,
                    a.content,
                    a.date_creation,
                    a.date_update,
                    a.count_view,
                COUNT(distinct c.id) as count_comment
                FROM article a
                LEFT JOIN comment c 
                    ON c.id_article = a.id
                GROUP BY
                    a.id,
                    a.title,
                    a.content,
                    a.count_view,
                    a.date_creation,
                    a.date_update
                ORDER BY ";
                if(isset($orderBy)) {
                    //controle d'injection url
                    if ($orderBy->column === 'title' || $orderBy->column === 'count_view'|| $orderBy->column === 'date_creation'|| $orderBy->column === 'count_comment'){
                        $sql .= $orderBy->column . " ";
                        if($orderBy->type ==="ASC" || $orderBy->type ==="DESC"){
                            $sql .= $orderBy->type;
                        }
                        else {
                            $sql .="ASC";
                        }
                    }
                    else {
                        $sql .= "a.id ASC";
                    }
                }
                else {
                    $sql .= "a.id ASC";
                }
        $result = $this->db->query($sql);
        $articles = [];
        $i = $j = 0;
        while ($article = $result->fetch()) {
            $countComment[] = $article['count_comment'];
            $articles[] = new Article($article);
            $i++;
        }
        // Je ne comprends pas pourquoi je suis obligé d'appliquer ce patch
        // théoriquement $articles[] devrait déjà contenir countComments
        while ( $j < $i) {
            $articles[$j]->setCountComments($countComment[$j]);
            $j++;
        }
        return $articles;
    }
    
    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id) : ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article) : void 
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article) : void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation, date_update) VALUES (:id_user, :title, :content, NOW(), NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article) : void
    {
        // select modification type (nb view ou article)
        $sqlControl = "SELECT count_view FROM article WHERE id = :id";
        $result = $this->db->query($sqlControl,['id'=>$article->getId()]);
        $countControl = $result->fetch();
        if($countControl['count_view'] == $article->getCountView()){
            $sql = "UPDATE article SET title = :title, content = :content, count_view = :count_view, date_update = NOW() WHERE id = :id";
            $this->db->query($sql, [
                'title' => $article->getTitle(),
                'content' => $article->getContent(),
                'id' => $article->getId(),
                'count_view' => $article->getCountView()
            ]);
        }
        else 
        {
            $sql = "UPDATE article SET count_view = :count_view WHERE id = :id";
            $this->db->query($sql, [
                'id' => $article->getId(),
                'count_view' => $article->getCountView()
            ]);
        }
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id) : void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
}
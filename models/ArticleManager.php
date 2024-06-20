<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager 
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     * 
     */
    public function getAllArticles(?Order $orderBy = null) : array
    {
        $sql = "SELECT * FROM article";
        switch($orderBy->type)
        {
            case "title" :
                $sql .= " ORDER BY $orderBy->type ";
                break;
            case "viewNumber" :
                $sql .= " ORDER BY count_view ";
                break;
            case "commentNumber" :
                $sql = " SELECT a.*, COUNT(c.id_article) as compteur 
                        FROM `article` a LEFT JOIN comment c ON c.id_article = a.id 
                        GROUP BY a.id ORDER BY compteur  ";
                break;
            case "publicationDate" :
                $sql .= " ORDER BY date_creation ";
                break;
        }
        switch($orderBy->upOrDown)
        {
            case "↓" :
                $sql .= "ASC";
                break;
            case "↑" :
                $sql .= "DESC";
                break;
        }
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
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

    public function countCommentsByArticle(Article $article) : int
    {
        $sql = "SELECT COUNT(id) FROM comment WHERE id_article = :id_article";
        $result = $this->db->query($sql, ['id_article' => $article->getId()]);
        $count = $result->fetch();
        return $count['COUNT(id)'];
    }

}
<?php 
/**
 * Contrôleur de la partie admin.
 */
 
class AdminController {

    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected() : void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm() : void 
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser() : void 
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser() : void 
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm() : void 
    {
        $this->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide. 
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article. 
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle() : void 
    {
        $this->checkIfUserIsConnected();

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");
        $countView = Utils::request("countView");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser'],
            'count_view'=> $countView
        ]);
        // On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle() : void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);
       
        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Visualisation de l'audience des articles.
     * @return void
     */
    public function showAudience() : void
    {
        $this->checkIfUserIsConnected();

        // données du navigateur
        $ordercolumn = Utils::request("ordercolumn", "a.id");
        $ordertype = Utils::request("ordertype", "ASC");
        

        $Order = new Order($ordercolumn, $ordertype );

        // On selectionne les articles dans l'ordre souhaité
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles($Order);

        // On ouvre la page
        $view = new View("Audience");
        $view->render("audience", ['articles' => $articles, 'column' => $ordercolumn, 'type' => $ordertype]);
    }

    /**
     * selection des informations en vu de la suppression d'un commentaire.
     * envoi vers une page dédiée
     * @return void
     */
    public function commentRazor()
    {
        $title = Utils::request("titleArticle", "");
        if (!isset($title)){
            // On redirige vers la page d'administration.
            Utils::redirect("admin");
        }

        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles(); 
        foreach ($articles as $article){
            if($article->getTitle() == $title)
            {
                $selectedArticle = $article;
            }
        }
        $commentsManager = new CommentManager;
        $comments = $commentsManager->getAllCommentsByArticleId($selectedArticle->getId());

        // On ouvre la page
        $view = new View("Suppression de commentaire");
        $view->render("commentRazor", ['selectedArticle' => $selectedArticle, 'comments' => $comments]);
        
    }
    /**
     * Suppression d'un commentaire.
     * @return void
     */
    public function deleteComment() : void
    {
        $idComment = Utils::request("commentslist", "");
        if (isset($idComment)){
            $comment= new Comment;
            $comment->setId($idComment);
            $commentsManager = new CommentManager;
            if($commentsManager->deleteComment($comment)){
                // On redirige vers la page d'administration.
                Utils::redirect("admin");
            }
            else{
                throw new Exception("Une erreur est survenue : aucun commentaire n'a été sélectionné");
            }
        }
        else{
            throw new Exception("Le commentaire n'existe pas.");
        }
    }


}
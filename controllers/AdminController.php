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

        // // On récupère les articles.
        // $articleManager = new ArticleManager();
        // $articles = $articleManager->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin");
    }

    /**
     * Affiche la page d'édition des articles
     * @return void
     */
    public function showEditionArticle() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // On affiche la page d'édition des articles
        $view = new View("EditionArticle");
        $view->render("editionArticle", [
            'articles' => $articles
        ]);
    }

    /**
     * Affiche la page de gestion des articles
     * @return void
     */
    public function showArticleManagement() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les commentaires.
        $commentManager = new CommentManager();

        $column = isset($_GET['column']) ? $_GET['column'] : "id" ;
        $order = isset($_GET['order']) ? $_GET['order'] : "desc";
        $comments = $commentManager->getAllCommentsWithArticleName($column, $order);

        
        // Regrouper les commentaires par article et calculer le nombre de commentaires pour chaque article
        $articles = [];
        foreach ($comments as $comment) {
            $articleId = $comment['id_article'];
            if (!isset($articles[$articleId])) {
                $articles[$articleId] = [
                    'title' => $comment['article_title'],
                    'date_creation' => $comment['article_date_creation'],
                    'comment_count' => 0
                ];
            }
            $articles[$articleId]['comment_count']++;
        }

        // On affiche la page de gestion des articles
        $view = new View("ArticleManagement");
        $view->render("articleManagement", [
            'articles' => $articles,
        ]);

    }

    public function showCommentManagement() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();
    
        $commentsPerPage = 5;
        $commentManager = new CommentManager();
        $page = isset($_GET['page']) ? $_GET['page'] : 1;


        $offset = ($page - 1) * $commentsPerPage;
        $comments = $commentManager->getAllCommentsWithArticleNamePaginated($commentsPerPage, $offset);
        $totalComments = $commentManager->getTotalCommentsCount();
        $totalPages = ceil($totalComments / $commentsPerPage);
    
        $view = new View("CommentManagement");
        $view->render("commentManagement", [
            'comments' => $comments,
            'currentPage' => $page,
            'totalPages' => $totalPages
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

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
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
        Utils::redirect("editionArticle");
    }

     /**
     * Suppression d'un commentaire.
     * @return void
     */
    public function deleteComment() : void
    {
        $this->checkIfUserIsConnected();
    
        $commentId = Utils::request("id", -1);
    
        // Récupérer le commentaire depuis la base de données
        $commentManager = new CommentManager();
        $comment = $commentManager->getCommentById($commentId);
    
        // Vérifier si le commentaire existe
        if ($comment !== null) {
            // Supprimer le commentaire
            $commentManager->deleteComment($comment);
        } else {
            $message = "Le commentaire que vous essayez de supprimer n'existe pas.";
            echo "<p>$message</p>";
        }
       
        // Rediriger vers la page d'administration.
        Utils::redirect("commentManagement");
    }

    public function showArticleListWithComments() : void
    {

        $this->checkIfUserIsConnected();

        // Récupérer la liste des articles avec le nombre de commentaires
        $articleManager = new ArticleManager();
        $articlesWithComments = $articleManager->getArticleListWithComments();

        // Afficher la nouvelle page avec les informations récupérées
        $view = new View("EditionArticle");
        $view->render("editionArticle", [
            'articlesWithComments' => $articlesWithComments,
        ]);
    }

}
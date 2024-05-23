<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager 
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles() : array
    {
        $sql = "SELECT * FROM article";
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
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
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

    /**
     * Enregistre la vue d'un article.
     */
    public function recordArticleView($articleId, $ip) 
    {
        $sql = "INSERT INTO article_views (article_id, date_visite, ip) VALUES (?, NOW(), ?)";
        $result = $this->db->getPDO()->prepare($sql);
        $result->execute([$articleId, $ip]);
    }
    
    /**
     * Récupère les articles en comptant le nombre de vues
     */
    public function getAllArticlesWithViews(string $column, string $order) {

        $allowed_columns = ['article.id', 'article_title', 'article_date_creation', 'comment_count', 'view_count'];
        $allowed_orders = ['ASC', 'DESC', 'asc', 'desc'];

        if (!in_array($column, $allowed_columns)) {
            throw new InvalidArgumentException('Invalid column name');
        }

        if (!in_array($order, $allowed_orders)) {
            throw new InvalidArgumentException('Invalid order');
        }

        $sql = "SELECT
            article.title AS article_title,
            article.date_creation AS article_date_creation,
            COUNT(DISTINCT comment.id) AS comment_count,
            COUNT(DISTINCT article_views.id) AS view_count
        FROM
            article
        LEFT JOIN
            comment 
            ON comment.id_article = article.id
        LEFT JOIN
            article_views
            ON article_views.article_id = article.id
        
        GROUP BY
            article.id
        ORDER BY 
            $column $order";
        $result = $this->db->getPDO()->query($sql);
        $result->execute();
        return $result->fetchAll(); 
    }


}
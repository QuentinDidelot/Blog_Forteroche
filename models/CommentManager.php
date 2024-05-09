<?php

/**
 * Cette classe sert à gérer les commentaires. 
 */
class CommentManager extends AbstractEntityManager
{
    
    /**
     * Récupère tous les commentaires d'un article.
     * @param int $idArticle : l'id de l'article.
     * @return array : un tableau d'objets Comment.
     */
    public function getAllCommentsByArticleId(int $idArticle) : array
    {
        $sql = "SELECT * FROM comment WHERE id_article = :idArticle";
        $result = $this->db->query($sql, ['idArticle' => $idArticle]);
        $comments = [];

        while ($comment = $result->fetch()) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    /**
     * Récupère un commentaire par son id.
     * @param int $id : l'id du commentaire.
     * @return Comment|null : un objet Comment ou null si le commentaire n'existe pas.
     */
    public function getCommentById(int $id) : ?Comment
    {
        $sql = "SELECT * FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $comment = $result->fetch();
        if ($comment) {
            return new Comment($comment);
        }
        return null;
    }


    /**
     * Ajoute un commentaire.
     * @param Comment $comment : l'objet Comment à ajouter.
     * @return bool : true si l'ajout a réussi, false sinon.
     */
    public function addComment(Comment $comment) : bool
    {
        $sql = "INSERT INTO comment (pseudo, content, id_article, date_creation) VALUES (:pseudo, :content, :idArticle, NOW())";
        $result = $this->db->query($sql, [
            'pseudo' => $comment->getPseudo(),
            'content' => $comment->getContent(),
            'idArticle' => $comment->getIdArticle()
        ]);
        return $result->rowCount() > 0;
    }

    /**
     * Supprime un commentaire.
     * @param Comment $comment : l'objet Comment à supprimer.
     * @return bool : true si la suppression a réussi, false sinon.
     */
    public function deleteComment(Comment $comment) : bool
    {
        $sql = "DELETE FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $comment->getId()]);
        return $result->rowCount() > 0;
    }
    

    public function getAllCommentsWithArticleName() : array
    {
        $sql = "SELECT article.title AS article_title, pseudo, comment.id, comment.content, id_article, comment.date_creation, article.date_creation AS article_date_creation FROM comment LEFT JOIN article ON article.id= comment.id_article";
        $result = $this->db->query($sql);
        return $result->fetchAll();
    }

    public function getAllCommentsWithArticleNamePaginated(int $commentsPerPage, int $offset) : array
{
    $sql = "SELECT article.title AS article_title, pseudo, comment.id, comment.content, id_article, comment.date_creation, article.date_creation AS article_date_creation FROM comment LEFT JOIN article ON article.id = comment.id_article LIMIT :limit OFFSET :offset";
    $result = $this->db->getPDO()->prepare($sql);
    $result->bindValue(':limit', $commentsPerPage, PDO::PARAM_INT);
    $result->bindValue(':offset', $offset, PDO::PARAM_INT);
    $result->execute();
    return $result->fetchAll();
}

    public function getCommentCountsByArticle() : array
    {
        $sql = "SELECT id_article, COUNT(*) AS comment_count FROM comment GROUP BY id_article";
        $result = $this->db->query($sql);
        return $result->fetchAll();
    }
    
    public function getCommentsForPage(int $commentsPerPage, int $offset) : array
    {
        $sql = "SELECT * FROM comment ORDER BY date_creation DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->bindParam(':limit', $commentsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    
    public function getTotalCommentsCount() : int
    {
        $sql = "SELECT COUNT(*) AS total FROM comment";
        $result = $this->db->query($sql);
        $data = $result->fetch();
        return $data['total'];
    }
    
}


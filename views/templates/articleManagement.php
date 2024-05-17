<?php
    /** 
     * Affichage de la partie admin "Gestion des articles"
     */
?>

<h2>Gestion des articles</h2>

<div class="adminArticle">

    <div class="articleLine">
        <div class="title">Titre de l'Article
            <a href="?action=articleManagement&column=article_title&order=asc">▲</a>
            <a href="?action=articleManagement&column=article_title&order=desc">▼</a>
        </div>

        <div class="title">Nombre de Commentaires
            <a href="?action=articleManagement&column=comment_count&order=asc">▲</a>
            <a href="?action=articleManagement&column=comment_count&order=desc">▼</a>
        </div>

        <div class="title">Date de Publication de l'Article           
            <a href="?action=articleManagement&column=article_date_creation&order=asc">▲</a>
            <a href="?action=articleManagement&column=article_date_creation&order=desc">▼</a></div>

        <div class="title">Nombre de vues
            <a href="?action=articleManagement&column=view_count&order=asc">▲</a>
            <a href="?action=articleManagement&column=view_count&order=desc">▼</a>
        </div>
    </div>

    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="article_title"><?= $article['article_title'] ?></div>
            <div class="comment_count"><?= $article['comment_count'] ?></div>
            <div class="date"><?= $article['article_date_creation'] ?></div>
            <div class="views"><?= $article['view_count'] ?></div>
        </div>
    <?php } ?>
    
</div>

<div class="back">
    <h3><a href="index.php?action=admin"> ← Retour</a></h3>
</div>
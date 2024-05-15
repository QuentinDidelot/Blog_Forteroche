<?php
    /** 
     * Affichage de la partie admin "Gestion des articles"
     */
?>

<h2>Gestion des articles</h2>

<div class="adminArticle">

    <div class="articleLine">
        <div class="title">Titre de l'Article
            <a href="?action=articleManagement&column=article.title&order=asc">▲</a>
            <a href="?action=articleManagement&column=article.title&order=desc">▼</a>
        </div>

        <div class="title">Nombre de Commentaires
            <a href="?action=articleManagement&column=comment_count&order=asc">▲</a>
            <a href="?action=articleManagement&column=comment_count&order=desc">▼</a>
        </div>

        <div class="title">Date de Publication de l'Article<a href="">            
            <a href="?action=articleManagement&column=article.date_creation&order=asc">▲</a>
            <a href="?action=articleManagement&column=article.date_creation&order=desc">▼</a></div>

        <div class="title">Nombre de vues
            <a href="?action=articleManagement&column=.......&order=asc">▲</a>
            <a href="?action=articleManagement&column=.......&order=desc">▼</a>
        </div>
    </div>

    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="article_title"><?= $article['title'] ?></div>
            <div class="comment_count"><?= $article['comment_count'] ?></div>
            <div class="date"><?= $article['date_creation'] ?></div>
        </div>
    <?php } ?>
</div>

<div class="back">
    <h3><a href="index.php?action=admin"> ← Retour</a></h3>
</div>
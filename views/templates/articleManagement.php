<?php
    /** 
     * Affichage de la partie admin "Gestion des articles"
     */
?>

<h2>Gestion des articles</h2>

<div class="adminArticle">

    <div class="articleLine">
        <div class="title">Titre de l'Article<a href="?action=articleManagement&column=title&order=asc">▲</a><a href="?action=articleManagement&column=title&order=desc">▼</a></div>
        <div class="title">Nombre de Commentaires<a href="">▲</a><a href="">▼</a></div>
        <div class="title">Date de Publication de l'Article<a href="">▲</a><a href="">▼</a></div>
        <div class="title">Nombre de vues<a href="">▲</a><a href="">▼</a></div>
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
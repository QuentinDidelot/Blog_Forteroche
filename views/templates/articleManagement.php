<?php
    /** 
     * Affichage de la partie admin "Gestion des articles"
     */
?>

<h2>Gestion des articles</h2>

<div class="adminArticle">
    <div class="sortButtons">
        <a href="?action=articleManagement&column=title&order=asc">Trier par Titre (Croissant)</a>
        <a href="?action=articleManagement&column=title&order=desc">Trier par Titre (Décroissant)</a>
    </div>

    <div class="articleLine">
        <div class="title">Titre de l'Article</div>
        <div class="title">Nombre de Commentaires</div>
        <div class="title">Date de Publication de l'Article</div>
    </div>

    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article['title'] ?></div>
            <div class="comment_count"><?= $article['comment_count'] ?></div>
            <div class="date"><?= $article['date_creation'] ?></div>
        </div>
    <?php } ?>
</div>

<div class="back">
    <h3><a href="index.php?action=admin"> ← Retour</a></h3>
</div>
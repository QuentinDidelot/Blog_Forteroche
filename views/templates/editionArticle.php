<?php
    /** 
     * Affichage de la partie admin "Edition des articles" : liste des articles avec un bouton "modifier" pour chacun. 
     * Et un formulaire pour ajouter un article. 
     */
?>

<h2>Edition des articles</h2>

<div class="adminArticle">

    <div class="articleLine">
            <div class="title">Titre de l'Article</div>
            <div class="title article_content">Contenu de l'article</div>
            <div class="title gestion_content">Gestion</div>
    </div>

    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="article_title"><?= $article->getTitle() ?></div>
            <div class="content"><?= $article->getContent(200) ?></div>
            <div><a class="submit" href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">Modifier</a></div>
            <div><a class="submit" href="index.php?action=deleteArticle&id=<?= $article->getId() ?>" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?> >Supprimer</a></div>
        </div>
    <?php } ?>
</div>


<a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>

<div class="back">
    <h3><a href="index.php?action=admin"> ← Retour</a></h3>
</div>
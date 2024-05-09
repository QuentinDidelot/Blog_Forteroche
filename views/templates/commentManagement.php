<?php
    /** 
     * Affichage de la partie admin "Gestion des commentaires"
     */
?>

<h2>Gestion des commentaires</h2>

<div class="adminArticle">
    <div class="articleLine">
        <div class="title">Titre de l'Article</div>
        <div class="title pseudo">Pseudonyme</div>
        <div class="title content">Commentaire</div>
        <div class="title date">Date de création du commentaire</div>
        <div class="title gestion">Gestion</div>

    </div>

    <?php foreach ($comments as $comment) { ?>
        <div class="articleLine">
            <div class="title"><?= $comment ["article_title"]?></div>
            <div class="pseudo"><?= $comment ["pseudo"]?></div>
            <div class="content"><?= $comment['content'] ?></div>
            <div class="date"><?= $comment['date_creation'] ?></div>
            <div class="gestion"><a class="submit" href="index.php?action=deleteComment&id=<?= $comment['id'] ?>" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?") ?> >Supprimer</a></div>
        </div>
    <?php } ?>

        
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="index.php?action=commentManagement&page=<?= $i ?>" <?= ($currentPage == $i) ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php } ?>
    </div>

</div>

<div class="back">
    <h3><a href="index.php?action=admin"> ← Retour</a></h3>
</div>
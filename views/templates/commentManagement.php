<?php
    /** 
     * Affichage de la partie admin "Gestion des commentaires"
     */
?>

<h2>Gestion des commentaires</h2>

<div class="adminArticle">
    <?php foreach ($comments as $comment) { ?>
        <div class="articleLine">
            <div class="title"><?= $comment['article_title'] ?></div>
            <div class="content"><?= $comment['content'] ?></div>
            <div class="date"><?= $comment['date_creation'] ?></div>
            <div><a class="submit" href="" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?") ?> >Supprimer</a></div>
        </div>
    <?php } ?>

</div>

<div class="back">
    <h3><a href="index.php?action=admin"> ← Retour</a></h3>
</div>
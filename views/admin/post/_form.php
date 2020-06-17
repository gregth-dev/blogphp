<form action="" method="POST" enctype="multipart/form-data">
    <?= $form->input('name', 'Titre') ?>
    <?= $form->input('slug', 'URL') ?>
    <?php if ($item->getImage()): ?>
    <div class="my-2"><img src="/uploads/posts/<?= $item->getImage() ?>" alt="image" width="200"></div>
    <?php endif ?>
    <?= $form->file('image', 'choisir une image') ?>
    <?= $form->chekbox('deleteImage', 'supprimer l\'image') ?>
    <?= $form->select('categories_ids', 'Catégories', $categories) ?>
    <?= $form->textarea('content', 'Contenu') ?>
    <?= $form->input('created_at', 'Date de création') ?>
    <?php if ($item->getId() !== null) : ?>
        <button class="btn btn-primary">Modifier l'article</button>
    <?php elseif ($item->getId() === null) : ?>
        <button class="btn btn-primary">Créer l'article</button>
    <?php endif ?>
</form>

<a href="<?= $router->url('new_post') ?>"><button class="btn btn-info my-2">Ajouter un article</button></a>
<?php if (isset($_GET['delete'])) : ?>
    <div class="alert alert-success mt-5">L'article a été supprimé</div>
<?php endif ?>
<?php if (isset($_GET['create'])) : ?>
    <div class="alert alert-success mt-5">L'article a été ajouté</div>
<?php endif ?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#ID</th>
            <th scope="col">Name</th>
            <th scope="col">Created_at</th>
            <th scope="col">Modifier</th>
            <th scope="col">Supprimer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
            <tr>
                <th scope="row"><?= $item->getId() ?></th>
                <td><?= $item->getName() ?></td>
                <td><?= $item->postDate() ?></td>
                <td>
                    <a href="<?= $router->url('edit_post', ['id' => $item->getId()]) ?>">
                        <button class="btn btn-info">Modifier</button>
                    </a>
                </td>
                <td>
                    <form action="<?= $router->url('delete_post', ['id' => $item->getId()]) ?>" method="POST">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez vous vraiment supprimer cet article ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
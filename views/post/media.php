<!-- Affichage d'un article -->
<div class="row">
    <div class="col-md-9">
        <div class="media">
            <img src="/uploads/posts/<?= $post->getImage() ?>" class="align-self-start mr-3" alt="image du media" width="250">
            <div class="media-body">
                <h5 class="mt-0"><?= $post->getName() ?></h5>
                <p><?= $post->getSlug() ?></p>
                <p><?= $post->postContent() ?></p>
                <p class="text-muted"><?= $post->postDate() ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="list-group">
            <?php foreach ($post->getCategories() as $category) : ?>
                <a href="<?= $router->url('category', ['id' => $category->getId(), 
                'slug' => $category->getSlug()]) ?>" class="list-group-item list-group-item-action">
                    <?= $category->getName(); ?>
                </a>
            <?php endforeach ?>
        </div>
    </div>
</div>
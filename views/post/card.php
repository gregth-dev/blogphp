<?php 
$listCategories = [];
foreach ($post->getCategories() as $category){
    $linkCategory = $router->url('category', ['id' => $category->getId(),'slug' => $category->getSlug()]); 
    $listCategories[] = <<<HTML
    <a href="{$linkCategory}" class="badge badge-info" >{$category->getName()}</a>
HTML;
}

?>

<div class="card mb-2">
    <div class="card-body">
        <p>
        <?= implode('',$listCategories) ?>
    <?php $post ?>
        </p>
        <img src="/uploads/posts/<?= $post->getImage() ?>" alt="image" width="150">
        <h5 class="card-title"><?= $post->getName() ?></h5>
        <p class="text-muted"><?= $post->postDate() ?></p>
        <p><?= $post->getExcerpt(); ?></p>
        <p>
            <a href="<?= $router->url('post', [
                            'slug' => $post->getSlug(),
                            'id' => $post->getId()
                        ])  ?>" class="btn btn-primary">Voir plus</a>
        </p>
    </div>
</div>
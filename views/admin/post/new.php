<?php

use App\Attachment\PostAttachment;
use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Manager\PostManager;
use App\Helpers\Objects;
use App\Manager\CategoryManager;
use App\Model\Post;
use App\Validators\PostValidator;

Auth::check();

$errors = [];
$item = new Post();

$fields = ['name', 'content', 'slug', 'created_at','image'];
$pdo = Connection::getPdo();
$categoryManager = new CategoryManager($pdo);
$categories = $categoryManager->list();



if (!empty($_POST)){
    $data = array_merge($_POST,$_FILES);
    $manager = new PostManager($pdo);
    $validator = new PostValidator($data, $manager, $item->getid(),$categories);
    Objects::hydrate($item,$data,$fields);
    if($validator->validate()){
        $pdo->beginTransaction();
        PostAttachment::upload($item,$data);
        $manager->create($item);
        $manager->addPostCategory($item->getid(),$_POST['categories_ids']);
        $pdo->commit();
        header('Location:'.$router->url('admin_post', ['id' => $item->getId()]).'?create=1');
        exit();
    }
    else{
        $errors = $validator->errors();
    }
}
$form = new Form($item,$errors);

?>

<h1>Ajouter un article</h1>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">l'article n'a pas été ajouté</div>
<?php endif ?>
<?php require '_form.php' ?>

 
<?php

use App\Attachment\PostAttachment;
use App\Manager\CategoryManager;
use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Manager\PostManager;
use App\Helpers\Objects;
use App\Manager\Post_CategoryManager;
use App\Validators\PostValidator;

Auth::check();

$pdo = Connection::getPdo();
$manager = new PostManager($pdo);
$categoryManager = new CategoryManager($pdo);
$categories = $categoryManager->list();
$item = $manager->find('id',$params['id']);
$categoryManager->hydratePosts([$item]);
$fields = ['name', 'content', 'slug', 'created_at','image'];
$errors = [];
$success = false;
if (!empty($_POST)){
    $data = array_merge($_POST,$_FILES);
    $validator = new PostValidator($data, $manager, $item->getid(),$categories);
    Objects::hydrate($item,$data,$fields);
    if($validator->validate()){
        
        $pdo->beginTransaction();
        $post_categoryManager = (new Post_CategoryManager($pdo))->deletePostCategory($item->getid());
        PostAttachment::upload($item,$data);
        $manager->update($item);
        $manager->addPostCategory($item->getid(),$_POST['categories_ids']);
        $pdo->commit();
        $categoryManager->hydratePosts([$item]);
        $success = true;
    }
    else{
        $errors = $validator->errors();
    }
}



$form = new Form($item,$errors);

?>

<h1>Editer l'article <?= $item->getName() ?></h1>

<?php if($success): ?>
<div class="alert alert-success">La mise à jour a été effectuée</div>
<?php endif ?>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">La mise à jour n'a pas été effectuée</div>
<?php endif ?>
<?php require '_form.php' ?>

 
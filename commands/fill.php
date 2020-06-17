<?php
//utilisation de la librairie faker

use App\Connection;

require dirname(__DIR__).'/vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');


//fichier qui va permettre de remplir la base de données
$pdo = Connection::getPdo();


//on vide les tables de la base de donnée
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');//on ignore les foreign key
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE user');

$posts = [];
$categories = [];

for($i = 1; $i < 51; $i++){
    $pdo->exec("INSERT INTO post SET name='{$faker->sentence()}', 
    slug='{$faker->slug()}', created_at=now(), 
    content='{$faker->paragraphs($nb = rand(3,15), $asText = true)}'");
    $posts[] = $pdo->lastInsertId();
}
for($i = 1; $i < 6; $i++){
    $pdo->exec("INSERT INTO category SET name='{$faker->sentence(2)}', 
    slug='{$faker->slug()}'");
    $categories[] = $pdo->lastInsertId();
}

foreach($posts as $post) {
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
    foreach($randomCategories as $category){
        $pdo->exec("INSERT INTO post_category SET post_id = $post, category_id = $category");
    }
}
$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='admin', password='$password'");
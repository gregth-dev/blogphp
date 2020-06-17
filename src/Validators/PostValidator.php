<?php

namespace App\Validators;

use App\Manager\PostManager;

class PostValidator extends AbstractValidator{

    public function __construct(array $data, PostManager $table, ?int $postId = null, array $categories)
    {
        parent::__construct($data);
        $this->validator->rule('required',['name', 'slug']);
        $this->validator->rule('lengthBetween',['name', 'slug'], 3, 200);
        $this->validator->rule('slug', 'slug');
        $this->validator->rule('subset','categories_ids', array_keys($categories));
        $this->validator->rule('image','image');
        $this->validator->rule(function ($fields, $value) use ($table, $postId) {
            return !$table->exist($fields, $value, $postId);
        }, ['slug', 'name'], 'Cette valeur est déjà utilisée');
    }

}
<?php

namespace App\Validators;

use App\Manager\CategoryManager;

class CategoryValidator extends AbstractValidator{

    public function __construct(array $data, CategoryManager $table, ?int $categoryId = null)
    {
        parent::__construct($data);
        $this->validator->rule('required',['name', 'slug']);
        $this->validator->rule('lengthBetween',['name', 'slug'], 3, 200);
        $this->validator->rule('slug', 'slug');
        $this->validator->rule(function ($fields, $value) use ($table, $categoryId) {
            return !$table->exist($fields, $value, $categoryId);
        }, ['slug', 'name'], 'Cette valeur est déjà utilisée');
    }

}
<?php

namespace App\Model;

use App\Helpers\Text;

class Category{

    private $id;
    private $slug;
    private $name;
    private $post_id;
    private $post;
    private $categories = [];

    public function getId(): ?int{
        return $this->id;
    }

    public function setId(int $id): self{
        $this->id = $id;
        return $this;
    }

    public function getPost_id(): ?int{
        return $this->post_id;
    }

    public function getSlug(): ?string{
        return Text::secure($this->slug);
    }

    public function setSlug(string $slug): self{
        $this->slug = $slug;
        return $this;
    }

    public function getName(): ?string{
        return Text::secure($this->name);
    }

    public function setName(string $name): self{
        $this->name = $name;
        return $this;
    }

    public function setPost (Post $post){
        $this->post = $post;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    public function getCategories(): array{
        return $this->categories;
    }
}
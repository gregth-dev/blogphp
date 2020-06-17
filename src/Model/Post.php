<?php

namespace App\Model;

use App\Constant;
use App\Helpers\Text;
use DateTime;
use Intervention\Image\ImageManager;

class Post{

    private $id;
    private $name;
    private $content;
    private $slug;
    private $created_at;
    private $image;
    private $categories = [];
    private $oldImage;
    private $pendingUpload = false;
    


    public function getName(): ?string{
        return Text::secure($this->name);
    }

    public function setName($name): self{
       $this->name = Text::secure($name);
       return $this;
    }
    
    public function getSlug(): ?string{
        return Text::secure($this->slug);
    }

    public function setSlug(string $slug): self{
        $this->slug = $slug;
        return $this;
    }
    
    public function getId(): ?int{
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getContent(): ?string{
        return $this->content;
    }
    
    public function setContent($content): self{
       $this->content = Text::secure($content);
       return $this;
    }

    public function getImage(): ?string{
        return $this->image;
    }

    public function setImage($image): self{
        if (is_array($image) && !empty($image['tmp_name'])){
            if(!empty($this->image)){
                $this->oldImage = $this->image;
            }
            $this->pendingUpload = true;
            $this->image = $image['tmp_name'];
        }
        if (is_string($image) && !empty($image)){
            $this->image = $image;
        }
        return $this;
    }

    public function getCreatedAt(): DateTime{
        return new DateTime($this->created_at);
    }
    
    public function setCreatedAt(string $created_at): self{
        $this->created_at= $created_at;
        return $this;
    }

    public function getCategories(): array{
        return $this->categories;
    }

    public function postContent(){
        return nl2br(Text::secure($this->content));
    }

    public function postDate(){
       return 'Publié le '.$this->getCreatedAt()->format('d-m-Y à H:i:s');
    }

    public function getExcerpt(): ?string{
        if($this->content === null){
            return null;
        }
        return nl2br(Text::secure(Text::excerpt($this->content, Constant::LIMIT_EXCERPT)));
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    public function addCategory(Category $category): void{
        $this->categories[] = $category;
        $category->setPost($this);
    }

    public function getCategoriesIds (): array
    {
        $ids = [];
        foreach($this->categories as $category) {
            $ids[] = $category->getId();
        }
        return $ids;
    }

    public function getPendingUpload(): bool {
        return $this->pendingUpload;
    }

    /**
     * Get the value of oldImage
     */ 
    public function getOldImage(): ?string
    {
        return $this->oldImage;
    }

}
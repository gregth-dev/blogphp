<?php

namespace App;

use App\Helpers\Url;
use Exception;
use PDO;

class Pagineted{

    private $query;
    private $querycount;
    private $items;
    private $pdo;
    private $perPage;
    private $count;


    public function __construct(
        string $query, 
        string $queryCount,
        ?PDO $pdo = null, 
        $perPage = Constant::PER_PAGE)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo ?: Connection::getPdo();
        $this->perPage = $perPage;
    }

    private function getCurrentPage(): int{
        return Url::getPositiveInt('page',1);
    }

    private function getPages(): float{
        if($this->count === null){
            $this->count = (int)$this->pdo
            ->query($this->queryCount)
            ->fetch(PDO::FETCH_NUM)[0];
        }
        return ceil($this->count / $this->perPage);
    }

    public function getItems(string $className): array
    {
        if($this->items === null){
            $currentPage = $this->getCurrentPage();
            $pages = $this->getPages();
            if($currentPage > $pages){
                throw new Exception('Cette page n\'existe pas');
            }
            $offset = $this->perPage * ($currentPage-1);
            $this->items = $this->pdo
            ->query($this->query." LIMIT {$this->perPage} OFFSET $offset")
            ->fetchall(PDO::FETCH_CLASS,$className);
        }
        return $this->items;
    }

    public function previousLink(string $link): ?string 
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if($currentPage > 2) $link .= "?page=".($currentPage - 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-primary mr-auto">&laquo; Page précédente</a>
HTML;
    }

    public function nextLink(string $link): ?string 
    {
        $currentPage = $this->getCurrentPage();
        
        $pages = $this->getPages();
        if($currentPage >= $pages) return null;
        $link .= "?page=".($currentPage + 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-primary ml-auto">Page suivante &raquo; </a>
HTML;
    }

}
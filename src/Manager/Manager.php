<?php

namespace App\Manager;

use App\Manager\Exception\NotFoundException;
use Exception;
use PDO;

abstract class Manager{

    protected $pdo;
    protected $table;
    protected $className;

    public function __construct(\PDO $pdo)
    {
        if ($this->table === null){
            throw new Exception("La class ".get_class($this). " n'a pas de propriété table");
        }
        if ($this->className === null){
            throw new Exception("La class ".get_class($this). " n'a pas de propriété className");
        }
        $this->pdo = $pdo;
    }

    public function find ($field,$attributs)
    {   
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE $field = :attributs");
        if ((int)$attributs){
            $query->bindValue(':attributs', $attributs, PDO::PARAM_INT);
        }
        $query->bindValue(':attributs', $attributs, PDO::PARAM_STR);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS,$this->className);
        $result = $query->fetch();
        if ($result === false){
            throw new NotFoundException($this->table, $attributs,$field);
        }
        
        return $result;
    }

    /**
     * Vérifie si une valeur existe dans la table
     * @param string $field Champs à rechercher
     * @param string $value valeur associée au champ
     */
    public function exist (string $field, $value, ?int $except = null): bool
    {
        $sql = "SELECT count(id) FROM {$this->table} WHERE $field = :field";
        if ($except !== null) {
            $sql .= " AND id != :except";
        }
        $req = $this->pdo->prepare($sql);
        $req->bindValue(':field', $value, PDO::PARAM_STR);
        if ($except !== null) {
            $req->bindValue(':except', $except, PDO::PARAM_INT);
        }
        $req->execute();
        return (int)$req->fetch(PDO::FETCH_NUM)[0] > 0;
    }

    public function delete(int $id, $item)
    {
    $req = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id LIMIT 1");
    $req->bindValue(':id',$id,PDO::PARAM_INT);
    $delete = $req->execute();
        if ($delete === false){
            throw new Exception("Impossible de supprimer $item");
        }
    }

    /**
     * @param $field nom du champ ORDER BY ex: name, id
     * @param $order nom du champ ASC ou DESC
     */
    public function readAll($field,$order)
    {
    $req = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY {$field} {$order}");
    $req->execute();
    $result = $req->fetchAll(PDO::FETCH_CLASS, $this->className);
        if ($result === false){
            throw new Exception("Impossible de récupérer les données de {$this->className}");
        }
    return $result;
    }
}
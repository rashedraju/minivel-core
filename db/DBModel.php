<?php

namespace App\core\db;

use App\core\Application;
use App\core\Model;

abstract class DBModel extends Model
{
    abstract static public function getTableName() : string;
    abstract public function getAttributes() : array;
    abstract public static function getPrimaryKey() : string;

    public function save(): bool
    {
        $table = $this->getTableName();
        $attributes = $this->getAttributes();
        $columns = implode(",", $attributes);
        $params = implode(",", array_map(function($attr) {
            return ":$attr";
        }, $attributes));

        $statement = self::prepare("INSERT INTO $table($columns) VALUES($params)");
        foreach ($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->$attribute);
        }
        return $statement->execute();
    }

    public static function prepare($sql){
        return Application::$app->database->pdo->prepare($sql);
    }

    public static function findOne(array $where)
    {
        $table = static::getTableName();
        $attributes = array_keys($where);
        $whereClause = implode("AND", array_map(function($attr){
            return "$attr = :$attr";
        }, $attributes));

        $statement = self::prepare("SELECT * FROM $table WHERE $whereClause");
        foreach ($attributes as $attr){
            $statement->bindValue($attr, $where[$attr]);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }
}
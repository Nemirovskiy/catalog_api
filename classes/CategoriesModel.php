<?php


class CategoriesModel extends Model
{
    protected $tableName = "category";
    protected $arColumn = array("id", "title", "parent_id");

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getArColumn()
    {
        return $this->arColumn;
    }

    public function getByID($id)
    {
        $name = $this->tableName;
        $query = "SELECT * from `{$name}` WHERE `id` = ?";
        $result = $this->DB->getQuery($query, array(intval($id)));
        return $result[0];
    }

    public function getByFilter($arFilter)
    {
        $sort = $this->getSortToSQL($arFilter["sort"]);
        $query = "SELECT * from `{$this->tableName}` {$sort} LIMIT :start,:max ";
        $arValues = array(
            "start" => $arFilter["start"],
            "max" => $arFilter["max"]
        );
        $result = $this->DB->getQuery($query, $arValues);
        return $result;
    }
}
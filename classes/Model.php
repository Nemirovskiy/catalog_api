<?php


abstract class Model
{
    protected $DB;
    protected $code;
    protected $arColumn = [];
    protected $tableName = "";

    public function __construct()
    {
        $this->DB = DB::getInstance();
    }

    public abstract function getByID($id);

    public abstract function getByFilter($arFilter);

    protected function getSortToSQL($sort)
    {
        $order = "ASC";
        if (substr($sort, 0, 1) === "-") {
            $order = "DESC";
            $sort = substr($sort, 1);
        }
        if (!in_array($sort, $this->arColumn)) {
            $sort = "id";
        }
        return "ORDER BY `{$sort}` {$order}";
    }

    public function getCode()
    {
        return $this->code;
    }

    public function sanitaiser($text)
    {
        $text = strip_tags($text);
        return $text;
    }

    public function getContent($arParams)
    {
        $result = array();
        if ($arParams["item"] > 0) {
            $result = $this->getByID($arParams["item"]);
        } else {
            $result = $this->getByFilter($arParams);
        }
        if (empty($result)) {
            $this->code = 404;
        }
        return $result;
    }

    private function checkFilter($arFilter)
    {
        $arFilter["title"];
        $arFilter["price"];
        $arFilter["producer"];
        $arFilter["categoryId"];
        $arFilter["parentCategoryId"];
    }
}
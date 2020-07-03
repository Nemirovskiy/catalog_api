<?php


class ProductsModel extends Model
{
    protected $tableName = "product";
    protected $tableLinkName = 'link';
    protected $arColumn = array(
        "id",
        "title",
        "short_description",
        "image_url",
        "amount",
        "price",
        "producer",
    );

    private function getQuery($arFilter = array())
    {
        $sort = $this->getSortToSQL($arFilter["sort"]);
        $category = new CategoriesModel();
        $catTable = $category->getTableName();
        $arColumn = $category->getArColumn();
        $select = "";
        foreach ($arColumn as $column) {
            $select .= ", c.{$column} AS C_{$column}";
        }
        $where = "";
        if($arFilter["id"] > 0){
            $where = " WHERE id = :id";
        }
        $limit = "";
        if(isset($arFilter["max"]) && isset($arFilter["start"])){
            $limit = "LIMIT :start,:max";
        }
        $query = "SELECT p.* {$select} FROM `{$catTable}` AS c ".
            "INNER JOIN (SELECT * FROM `{$this->tableName}` {$where} {$sort} {$limit}) AS p ".
            "INNER JOIN `{$this->tableLinkName}` AS l ON l.product_id = p.id ".
            "WHERE c.id = l.category_id";
        return $query;
    }

    public function getByID($id)
    {
        $query = $this->getQuery(array("id"=>$id));
        $result = $this->DB->getQuery($query, array("id" => intval($id)));
        $arResult = array();
        foreach ($result as $i => $arValue) {
            foreach ($arValue as $code => $value) {
                if (in_array($code, $this->arColumn) && !isset($arResult[$code])) {
                    $arResult[$code] = $value;
                } elseif (strpos($code, 'C_') !== false) {
                    $arResult["category"][$i][substr($code, 2)] = $value;
                }
            }
        }
        return $arResult;
    }

    public function getByFilter($arFilter)
    {
        $query = $this->getQuery($arFilter);
        $arValue = array(
            "max"=>$arFilter["max"],
            "start"=>$arFilter["start"],
        );
        $result = $this->DB->getQuery($query, $arValue);
        $arResult = array();
        foreach ($result as $i => $arValue) {
            $arCategory = array();
            foreach ($arValue as $code => $value) {
                if (in_array($code, $this->arColumn) && !isset($arResult[$code])) {
                    $arResult[$arValue["id"]][$code] = $value;
                } elseif (strpos($code, 'C_') !== false) {
                    $arCategory[substr($code, 2)] = $value;
                }
            }
            $arResult[$arValue["id"]]["category"][] = $arCategory;
        }
        return $arResult;
    }
}
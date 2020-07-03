<?php


class InstallProducts extends ProductsModel
{
    function addItem($arItem)
    {
        $arItem['title'] = $this->sanitaiser($arItem['title']);
        $categories = array();
        $setString = "";
        foreach ($arItem as $code => $value) {
            if ($code === "categories") {
                foreach ($value as $arCategories) {
                    $categories[] = $arCategories["id"];
                }
                unset($arItem[$code]);
            } elseif (!in_array($code, $this->arColumn) || $code === "id") {
                unset($arItem[$code]);
            } else {
                $setString .= " `{$code}` = :$code,";
            }
        }
        $setString = substr($setString, 0, -1);

        $query = "INSERT INTO `{$this->tableName}` SET {$setString}";
        $id = $this->DB->getExec($query, $arItem);
        if ($id > 0 && !empty($categories)) {
            $query = "INSERT INTO `{$this->tableLinkName}` SET `product_id` = :product, `category_id` = :category";
            foreach ($categories as $category) {
                $arValue[] = array("product" => $id, "category" => $category);
            }
            $this->DB->multiExec($query, $arValue);
        }
    }
}
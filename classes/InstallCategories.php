<?php


class InstallCategories extends CategoriesModel
{
    public function addItem($arItem)
    {
        $arItem['title'] = $this->sanitaiser($arItem['title']);
        foreach ($arItem as $code => $value) {
            if (!in_array($code, $this->arColumn) || $code === "id") {
                unset($arItem[$code]);
            }
        }
        $query = "INSERT INTO `{$this->tableName}` SET `title` = :title, `parent_id` = :parent_id";
        $this->DB->getExec($query, $arItem);
    }
}
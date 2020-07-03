<?php


class Install
{
    protected $DB;

    public function __construct()
    {
        $this->DB = DB::getInstance();
    }

    public function categoriesFromFile($file)
    {
        $this->scanFile($file, "InstallCategories");
    }

    public function productsFromFile($file)
    {
        $this->scanFile($file, "InstallProducts");
    }

    protected function scanFile($file, $className)
    {
        $category = new $className;
        $dataJson = file_get_contents($file);
        $arData = json_decode($dataJson, true);
        foreach ($arData['data'] as $arItem) {
            $category->addItem($arItem);
        }
    }

    public function installDB($installSqlFile)
    {
        $query = file_get_contents($installSqlFile);
        $this->DB->getExec($query, array());
    }
}
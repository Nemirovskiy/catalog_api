<?php


class Controller
{
    private $arParams;
    private $code;

    function __construct()
    {
        $this->parsePath();
    }

    protected function parseQuery($query)
    {
        $arResult = array();
        $arQuery = explode("&", $query);
        foreach ($arQuery as $item) {
            $arValue = explode("=", $item);
            $arResult[$arValue[0]] = $arValue[1];
        }
        return $arResult;
    }

    private function parsePath()
    {
        if ($_SERVER['REQUEST_URI'] != '/') {
            $arParse = parse_url($_SERVER['REQUEST_URI']);
            $arParse["query"] = $this->parseQuery($arParse["query"]);
            if ($arParse["query"]["filter"]) {
                $this->arParams["filter"] = $arParse["query"]["filter"];
            }
            if ($arParse["query"]["sort"]) {
                $this->arParams["sort"] = $arParse["query"]["sort"];
            }
            $this->arParams["start"] = intval($arParse["query"]["startFrom"]);
            $max = intval($arParse["query"]["maxItems"]);
            $this->arParams["max"] = ($max > 0) ? $max : API_MAX_ITEM;
            $arParam = explode('/', $arParse["path"]);
            if (count($arParam) >= 4) {
                list(
                    ,
                    $this->arParams["controller"],
                    $this->arParams["version"],
                    $this->arParams["model"],
                    $this->arParams["item"]
                    ) = $arParam;
                $this->arParams["controller"] = strtolower($this->arParams["controller"]);
                $this->arParams["version"] = strtolower($this->arParams["version"]);
                $this->arParams["model"] = strtolower($this->arParams["model"]);
            }
        }
    }


    function getModel()
    {
        return ucfirst($this->arParams["model"]) . "Model";
    }

    public function run()
    {
        $model = $this->getModel();
        $data = null;
        $this->code = 405;
        if (class_exists($model) && $this->arParams["controller"] === "api") {
            $class = $this->getModel();
            $model = new $class;
            $data = $model->getContent($this->arParams);
            $this->code = $model->getCode();
        }
		$this->json(["data"=>$data]);
    }

    private function json($data)
    {
        $viewer = new Viewer($data, $this->code);
        echo $viewer->toJson();
    }
}
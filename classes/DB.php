<?php


class DB
{
    /**
     * @var $connect PDO
     */
    private static $connect;
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    private function __construct()
    {
        $dsn = DB_TYPE . ":dbname=" . DB_NAME . ";host=" . DB_HOST;
        self::$connect = new PDO($dsn, DB_USER, DB_PASSWORD);
    }

    public function getExec($query, $value)
    {
        $statement = self::$connect->prepare($query);
        $result = $statement->execute($value);
        if ($result) {
            $result = self::$connect->lastInsertId();
        }
        return $result;
    }

    public function multiExec($query, $arValue)
    {
        $statement = self::$connect->prepare($query);
        foreach ($arValue as $value) {
            $statement->execute($value);
        }
    }

    public function getQuery($query, $value)
    {
        $statem = self::$connect->prepare($query);
        if (isset($value["max"])) {
            echo "max = ".$value["max"];
            $statem->bindValue(":max", $value["max"], PDO::PARAM_INT);
            unset($value["max"]);
        }
        if (isset($value["start"])) {
            $statem->bindValue(":start", $value["start"], PDO::PARAM_INT);
            unset($value["start"]);
        }
        if (empty($value)) {
            echo  "1";
            $statem->execute();
        } else {
            echo "2";
            $statem->execute($value);
        }
        return $statem->fetchAll(PDO::FETCH_ASSOC);
    }
}
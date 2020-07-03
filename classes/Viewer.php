<?php


class Viewer
{
    private $data;

    function __construct($data, $code = 200)
    {
        $this->data = $data;
        header("HTTP/1.1 " . $code . " " . $this->getStatus($code));
    }

    public static function toScreen($data)
    {
        echo "<b>" . __METHOD__ . "</b><pre>" . print_r($data, true) . "</pre>";
    }

    public function toJson()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        return json_encode($this->data);
    }

    private function getStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }
}
<?php
spl_autoload_register('autoload');

function autoload($name)
{
	require_once __DIR__. DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "{$name}.php";
}

require_once '.settings.php';

if($_GET["install"] === "Y"){
	$dir = __DIR__. DIRECTORY_SEPARATOR . "install" . DIRECTORY_SEPARATOR ;
	$installer = new Install();
	$installer->installDB($dir."install.sql");
	$installer->categoriesFromFile($dir."categories.json");
	$installer->productsFromFile($dir."products.json");
}else{
	$controller = new Controller();
	$controller->run();
}
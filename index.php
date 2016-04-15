
<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

define('PATH_ABSOLUTO_DIRECTORIO_BASE_FILER', '/mnt/filer/html/desarrollo');
define('PATH_ABSOLUTO_DIRECTORIO_BASE_LIBRERIA_SMARTY', PATH_ABSOLUTO_DIRECTORIO_BASE_FILER . '/lib/smarty3/libs');

require_once __DIR__ . '/vendor/autoload.php';

if (isset($_GET['portal'])) {
    $input['portal'] = $_GET['portal'];
    $input['col'] = isset($_GET['col']) ? $_GET['col']:NULL;
    $input['fil'] = isset($_GET['fil']) ? $_GET['fil']:NULL;
    $input['width'] = isset($_GET['width']) ? $_GET['width']:NULL;
    
    $api = new Api();
    $datos = $api->getDatosAPIs($input);
} else {
    $datos = 'la url es : ' . $_SERVER['PHP_SELF'] . '/?portal=PORTAL_NECESARIO';
}
echo $datos;
?>

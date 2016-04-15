
<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

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

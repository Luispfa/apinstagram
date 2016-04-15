<?php

class Api
{

    public function getDatosAPIs($input)
    {
        $api = new Instagram(__DIR__ . '/../config/api_config_instagram.ini', $input['portal']);
        
        $api->setPortal($input['portal']);
        $api->setColumn($input['col']);
        $api->setFill($input['fil']);
        $api->setWidth($input['width']);
        $api->setConfigIni();

        $elementos = $api->getElementos();
        $respuesta = array();
        foreach ($elementos as $key => $value) {
            $respuesta[] = $api::getInfoObj($value);
        }
        $username = $respuesta[0]->username;
        $width = $respuesta[0]->width;
        $column = $respuesta[0]->column;
        
        $out = array('data' => $this->ordenarMuro($respuesta), 
                    "username" => $username,
                    "width" => $width,
                    "column"=> $column
                    );
        return SmartyController::initSmarty($out, __DIR__ . '/../template/view.tpl');
    }

    public function ordenarMuro($elementos_a_publicar)
    {
        if (!is_null($elementos_a_publicar)) {
            $aux = array();
            $i = 1;
            foreach ($elementos_a_publicar as $key => $row) {
                $aux[$key] = $row->created_time;
                $i++;
            }
            array_multisort($aux, SORT_DESC, $elementos_a_publicar);
        }

        return $elementos_a_publicar;
    }

}

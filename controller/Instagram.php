<?php

class Instagram
{

    private $_url = '';
    private $_numDatos = 100;
    private $_arrayHashtag = array();
    private $_claveInicial;
    private $_config = array();
    private $_token;
    static  $_column;
    private $_fill;
    private $_portal;
    static  $_width;

    public function __construct($path_config_ini)
    {
        $this->_config = Configuracion::cargarConfiguracionArchivo($path_config_ini);
    }

    public function setPortal($portal)
    {
        $this->_portal = $portal;
    }

    public function setColumn($column)
    {
        self::$_column = $column == NULL ? 1 : $column;
    }

    public function setFill($fill)
    {
        $this->_fill = $fill == NULL ? 1 : $fill;
    }
    
    public function setWidth($width)
    {
        self::$_width = $width == NULL ? '100%' : $width;
    }

    public function setConfigIni()
    {
        if (isset($this->_config[$this->_portal]) && count($this->_config[$this->_portal]) > 0) {
            $this->_url = $this->_config[$this->_portal]['url'];
            $this->_token = $this->_config[$this->_portal]['access_token'];
            $this->_numDatos = self::$_column * $this->_fill;
            $this->_claveInicial = $this->_config[$this->_portal]['claveInicial'];
        } else {
            die('no existe el portal');
        }
    }

    public static function getInfoObj($json)
    {
        $Obj = new \stdClass();
        $Obj->Thumbnail = $json->images->thumbnail->url;
        $Obj->Thumbnail_width = $json->images->thumbnail->width;
        $Obj->Thumbnail_height = $json->images->thumbnail->height;
        $Obj->Thumbnail = $json->images->thumbnail->url;
        $Obj->Link = $json->link;
        $Obj->title = isset($json->html) ? strip_tags($json->html) : '';
        $Obj->username = $json->user->username;
        
        $Obj->width = self::$_width;
        $Obj->column = self::$_column;
        
        $date = new DateTime();
        $date->setTimestamp($json->created_time);
        $Obj->created_time = $date->format('Y-m-d H:i:s');

        return $Obj;
    }

    public function getElementos()
    {
        $respuesta = null;
        $instagramJson = $this->instagramRequest();

        if (is_null($instagramJson) || count($instagramJson) == 0) {
            return null;
        }

        // Establecemos la salida $data hacia la clave del servicio (instagram)
        // y por cada hashTag definido en la salida.
        foreach ($instagramJson as $hashtag => $json) {
            $data[$this->_claveInicial][$hashtag] = json_decode($json);
        }

        foreach ($data[$this->_claveInicial] as $hashtag => $jsonPhp) {
            if (isset($jsonPhp->data) && count($jsonPhp->data) > 0) {
                foreach ($jsonPhp->data as $imgPorHashtag) {
                    $id = $this->_claveInicial . '-' . $hashtag . '-' . $imgPorHashtag->id;
                    $respuesta[$id] = $imgPorHashtag;
                    if (isset($imgPorHashtag->caption->text)) {
                        $html = $this->escribeTextEnHtml($imgPorHashtag->caption->text);
                        $respuesta[$id]->html = $html;
                        unset($html);
                    }
                    unset($id);
                }
            } else {
                continue;
            }
        }

        return $respuesta;
    }

    private function instagramRequest()
    {
        $count = "&count=" . $this->_numDatos;
        $array_salida = array();

        $q = array();
        $str_url = $this->_url . $this->_token . $count;
        $jsn = file_get_contents($str_url);
        $obj = json_decode($jsn, true);
        if (isset($obj["meta"]["code"]) && $obj["meta"]["code"] == 200) {

            if (isset($obj["data"]) && count($obj["data"]) > 0) {
                $new_json = $obj["data"];
                $counter = round($this->_numDatos);
                // get all pages
                while ($counter > 0) {
                    $q["data"] = $new_json;
                    $q["meta"] = $obj["meta"];
                    $q["pagination"] = $obj["pagination"];
                    break;
                    $counter--;
                }
                if (count($q) > 1) {
                    $array_salida[] = json_encode($q);
                }
            }
        } else {
            $array_salida[] = null;
        }
        return $array_salida;
    }

    private function escribeTextEnHtml($text)
    {
        $text = preg_replace("/#([\p{L}|\p{N}|_]*)/u", "<a href=\"https://instagram.com/explore/tags/$1\">#$1</a>", $text);
        $text = preg_replace("/(?<=^|(?<=[^a-zA-Z0-9-_\\.]))@([\p{L}\p{N}_.]*)/u", "<a href=\"https://instagram.com/$1\">@$1</a>", $text);

        return $text;
    }

    /**
     * Define los hashtags
     * @param string $hashtags
     */
    public function setHashTags($hashtags)
    {
        $this->_hashtags = $hashtags;
        $this->_arrayHashtag = $this->getHashtagsArray($hashtags);
    }

    /**
     * Establece el número de datos a recuperar
     * @param type $numero
     */
    public function setNumeroDatos($numero)
    {
        $this->_numDatos = $numero;
    }

    /**
     * Devuelve el número de datos que se van a recuperar
     * @return integer
     */
    public function getNumeroDatos()
    {
        return $this->_numDatos;
    }

    /**
     * Devuelve los hashtags como array, a partir de un string de entrada separado por comas ','
     * @param string $hashTags
     * @return array
     */
    private function getHashtagsArray($hashTags)
    {
        $array = explode(',', $hashTags);
        return array_map('trim', $array);
    }

    public function setQueryParams($queryParams, $objectBaneados)
    {
        
    }

}

<?php

abstract class SmartyController
{

    public static function initSmarty($datos = null, $template)
    {
        // Class Constructor. These automatically get set with each new instance.
        $smarty = new c_template;

        $smarty->compile_check = true;
        $smarty->template_dir = __DIR__ . '/../template';
        $smarty->compile_dir = __DIR__ . '/../cache_smarty';

        foreach ($datos as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch($template);
    }

}

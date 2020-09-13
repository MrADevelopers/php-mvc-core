<?php

namespace mradevelopers\phpmvc\form;

use mradevelopers\phpmvc\model;

class Form
{
    public static function begin($action,$method)
    {
        echo sprintf('<form action="%s" method="%s">',$action,$method);
        return new Form();
    }

    public static function end()
    {
        return '</form>';
    }

    public function field(Model $model,$attr)
    {
        return new InputField($model,$attr);
    }


}

?>
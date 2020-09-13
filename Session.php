<?php

namespace app\core;

class Session
{

    protected const FLASH_KEY = 'flash_msg';

    public function __construct()
    {
        session_start();

  
        $msg = $_SESSION[self::FLASH_KEY] ?? [] ;

        foreach($msg as $key=>&$value)
        {
            $value['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $msg;
      
    }

    public function __destruct()
    {
        $msg = $_SESSION[self::FLASH_KEY] ?? [] ;

        foreach($msg as $key=>&$value)
        {
            if($value['remove'])
            {
                unset($msg[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $msg;
    }

    public function setFlash($key,$msg)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove'=>false,
            'value'=>$msg
        ];
    }

    public function getFlash($key)
    {
         return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key,$value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }


}


?>
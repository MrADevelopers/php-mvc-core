<?php

namespace mradevelopers\phpmvc\exceptions;

class ForbiddenException extends \Exception
{

    protected $code = 403;
    protected $message = 'Opps! you don\'t have permission';

    

}


?>
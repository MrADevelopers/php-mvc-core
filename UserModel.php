<?php

namespace mradevelopers\phpmvc;

use mradevelopers\phpmvc\db\DbModel;

abstract class UserModel extends DbModel
{

     abstract public function getDisplayName(): string; 

}


?>
<?php

namespace app\core\form;
use app\core\model;



class InputField extends BaseField
{

    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public String  $type;
 

    public function __construct(Model $model, string $attr)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model,$attr);
    }

    

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf('<input type="%s" class="form-control %s" name="%s" value="%s" />', $this->type,
        $this->model->hasError($this->attr) ? 'is-invalid' : '',
        $this->attr,
        $this->model->{$this->attr},);
    }


}

?>
<?php

namespace app\core\form;
use app\core\model;



abstract class BaseField
{
    public Model $model;
    public string $attr;

    abstract public function renderInput(): string;

    public function __construct(Model $model, string $attr)
    {
        $this->model = $model;
        $this->attr = $attr;
        
    }

    public function __toString()
    {
        return sprintf('

        <div class="form-group">
            <label>%s</label>
                %s            
            <span class="invalid-feedback">%s</span>
        </div>',
        $this->model->getLabel($this->attr),
        $this->renderInput(),
        $this->model->getFirstError($this->attr));
    }

}

?>
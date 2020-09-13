<?php

namespace mradevelopers\phpmvc\form;


class TextAreaField extends BaseField
{

    public function renderInput(): string
    {

        return sprintf('<textarea class="form-control %s" name="%s">%s</textarea>',
            $this->model->hasError($this->attr) ? 'is-invalid' : '',
            $this->attr,
            $this->model->{$this->attr}
        );

    }


}



?>
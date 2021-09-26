<?php

namespace Minivel\Form;

use Minivel\Model;

class TextareaField extends Field
{
    public function __construct(Model $model, string $attribute)
    {
        parent::__construct($model, $attribute);
    }

    public function renderField(): string
    {
        return sprintf('
            <textarea name="%s" class="Form-control %s"> </textarea>
        ',
            $this->attribute,
            $this->model->hasError($this->attribute) ? "is-invalid" : "",
        );
    }
}
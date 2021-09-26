<?php

namespace Minivel\Form;

use Minivel\Model;

class InputField extends Field
{
    public string $type;
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }


    public function passwordField() : Field {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function renderField(): string
    {
        return sprintf('
            <input type="%s" name="%s" value="%s" class="Form-control %s">
        ',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? "is-invalid" : ""
        );
    }
}
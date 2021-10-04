<?php

namespace Minivel\Form;

use Minivel\Model;

class Form
{
    public static function begin(string $action, string $method): Form{
        echo sprintf('<form action=%s method=%s>', $action, $method);
        return new Form;
    }

    public function inputField(Model $model, string $attribute) : InputField {
        return new InputField($model, $attribute);
    }

    public function textareaField(Model $model, string $attribute) : TextareaField {
        return new TextareaField($model, $attribute);
    }

    public function end(){
        echo "</form>";
    }
}
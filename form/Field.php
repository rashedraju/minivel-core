<?php

namespace App\core\form;

use App\core\Model;

abstract class Field
{
    public Model $model;
    public string $attribute;

    abstract public function renderField(): string;

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf('
            <div class="mb-3">
                <label for="%s" class="form-label"> %s </label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ',
            $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->renderField(),
            $this->model->getFirstError($this->attribute)
        );
    }

}
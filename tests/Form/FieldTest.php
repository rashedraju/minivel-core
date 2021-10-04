<?php

namespace Form;

use Minivel\Form\Field;
use Minivel\Model;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function testAbstractFieldToStringMagicMethod(){
        $model = new class extends Model{
            public function getRules(): array
            {
                return [];
            }
            public function getLabels(): array
            {
                return [];
            }
            public function getLabel($attribute): string
            {
                return "Name";
            }
            public function getFirstError($attribute): string
            {
                return "";
            }
        };
        $field = new class($model, "name") extends Field{
            public function __construct(Model $model, string $attribute)
            {
                parent::__construct($model, $attribute);
            }
            public function renderField(): string
            {
                return "field";
            }
            public function executeParentToStringMagicMethod(){
                return parent::__toString();
            }
        };
        $expected = '
            <div class="mb-3">
                <label for="name" class="Form-label"> Name </label>
                field
                <div class="invalid-feedback">
                    
                </div>
            </div>
        ';
        $this->assertSame($expected, $field->executeParentToStringMagicMethod());
    }
}

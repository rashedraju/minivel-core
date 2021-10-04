<?php

namespace Form;

use Minivel\Form\Field;
use Minivel\Form\InputField;
use Minivel\Model;
use PHPUnit\Framework\TestCase;

class InputFieldTest extends TestCase
{
    public function testRenderFieldReturnInputField(){
        $model = new class extends Model{
            public string $name = "";
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
        $inputField = new InputField($model, "name");
        $expected = '
            <input type="text" name="name" value="" class="form-control ">
        ';
        $this->assertSame($expected, $inputField->renderField());
    }
}

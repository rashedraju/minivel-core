<?php

namespace Form;

use Minivel\Form\TextareaField;
use Minivel\Model;
use PHPUnit\Framework\TestCase;

class TextareaFieldTest extends TestCase
{
    public function testRenderFieldReturnTextareaField(){
        $model = new class extends Model{
            public string $body = "";
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
                return "Body";
            }
            public function getFirstError($attribute): string
            {
                return "";
            }
        };
        $textareaField = new TextareaField($model, "body");
        $expected = '
            <textarea name="body" class="Form-control "> </textarea>
        ';
        $this->assertSame($expected, $textareaField->renderField());
    }
}

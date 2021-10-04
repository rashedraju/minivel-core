<?php

namespace Form;

use Minivel\Form\Form as FormCore;
use Minivel\Form\InputField;
use Minivel\Form\TextareaField;
use Minivel\Model;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testFormBeginEchoBeginningAndReturnFormInstance(): FormCore
    {
        $this->expectOutputString("<form action=/ method=post>");
        $form = FormCore::begin("/", "post");
        $this->assertInstanceOf(FormCore::class, $form);
        return $form;
    }

    /**
     * @depends testFormBeginEchoBeginningAndReturnFormInstance
     */
    public function testFormEndReturnEndingOfForm($form){
        $this->expectOutputString("</form>");
        $form->end();
    }

    /**
     * @depends testFormBeginEchoBeginningAndReturnFormInstance
     */
    public function testInputFieldAndTextareaFieldReturnFieldInstance($form){
        $model = new class() extends Model{
            public function getRules(): array
            {
                return [];
            }
            public function getLabels(): array
            {
                return [];
            }
        };
        $this->assertInstanceOf(InputField::class, $form->inputField($model, "name"));
        $this->assertInstanceOf(TextareaField::class, $form->textareaField($model, "name"));
    }
}

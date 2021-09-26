<?php

namespace Minivel;

abstract class Model
{
    public const RULE_REQUIRE = 'require';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];

    abstract public function rules() : array;
    abstract public function getLabels() : array;

    public function loadData($data){
        foreach ($data as $key => $value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }

    public function validate(): bool{
        foreach ($this->rules() as $attribute => $rules){
            $value = $this->$attribute;
            $label = $this->getLabel($attribute);

            foreach ($rules as $rule){
                $ruleName = $rule;

                if(is_array($rule)){
                    $ruleName = $rule[0];
                }

                if($ruleName === self::RULE_REQUIRE && empty($value)){
                    $this->setError($attribute, "$label is required.");
                }

                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->setError($attribute, "Please enter valid $label");
                }

                if($ruleName === self::RULE_MIN && strlen($value) < $rule[1]){
                    $this->setError($attribute, "Min length of $label must be $rule[1]");
                }

                if($ruleName === self::RULE_MAX && strlen($value) > $rule[1]){
                    $this->setError($attribute, "max length of $label must be" . $this->getLabel($rule[1]));
                }

                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule[1]}){
                    $this->setError($attribute, "The $label must be the same as " . $this->getLabel($rule[1]));
                }

                if($ruleName === self::RULE_UNIQUE){
                    $className = $rule[1];
                    $tableName = $className::getTableName();

                    $statement = Application::$app->database->prepare("SELECT * FROM $tableName WHERE $attribute=:$attribute");
                    $statement->bindValue(":$attribute", $this->$attribute);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record){
                        $this->setError($attribute, "$label already exists");
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function setError($attribute, $msg){
        $this->errors[$attribute][] = $msg;
    }

    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }

    public function getLabel($attribute){
        return $this->getLabels()[$attribute] ?? "";
    }
}
<?php

namespace mradevelopers\phpmvc;

abstract class Model
{

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];

    public function loadData($data)
    {
        foreach($data as $key=>$value)
        {
            if(property_exists($this,$key))
            {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attr)
    {
        return $this->labels()[$attr] ?? $attr;
    }

    public function validate()
    {
        foreach($this->rules() as $attr=>$rules)
        {
            $value = $this->{$attr};
            foreach($rules as $rule)
            {
                $ruleName = $rule;

                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                }

                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorForRule($attr,self::RULE_REQUIRED);
                }

                if($ruleName === self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attr,self::RULE_EMAIL);
                }

                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addErrorForRule($attr,self::RULE_MIN,$rule);
                }

                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addErrorForRule($attr,self::RULE_MAX,$rule);
                }

                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']} ){
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attr,self::RULE_MATCH,$rule);
                }

                if($ruleName === self::RULE_UNIQUE){
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attr;
                    $tableName = $className::tableName();

                    $stmt = Application::$app->db->prepare("SELECT *FROM $tableName WHERE $uniqueAttr = :attr");
                    $stmt->bindValue(":attr",$value);
                    $stmt->execute();
                    $record = $stmt->fetchObject();

                    if($record)
                    {
                        $this->addErrorForRule($attr,self::RULE_UNIQUE,['field'=>$this->getLabel($attr)]);
                    }


                }

            }
        }

        return empty($this->errors);
    }


    private function addErrorForRule(string $attr, string $rule, $params = [])
    {
        $msg = $this->errorMessage()[$rule] ?? '';

        foreach($params as $key=>$value)
        {
            $msg = str_replace("{{$key}}",$value,$msg);
        }

        $this->errors[$attr][] = $msg;
    }

    public function addError(string $attr, string $msg)
    {
        
        $this->errors[$attr][] = $msg;
    }

    public function errorMessage()
    {
        return [
            self::RULE_REQUIRED=>'This field is required',
            self::RULE_EMAIL=>'This field must be valid email address',
            self::RULE_MIN=>'Min length of this field must be {min}',
            self::RULE_MAX=>'Max length of this field must be {max}',
            self::RULE_MATCH=>'This field must be the same as {match}',
            self::RULE_UNIQUE=>'Record with {field} already exists',
        ];
    }

    public function hasError($attr)
    {
        return $this->errors[$attr] ?? false;
    }

    public function getFirstError($attr)
    {
        return $this->errors[$attr][0] ?? false;
    }

}


?>
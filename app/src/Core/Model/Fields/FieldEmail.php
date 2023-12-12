<?php

namespace App\Core\Model\Fields;

use App\Core\Model\ModelValidateException;

class FieldEmail extends FieldVarchar
{
    public function validate(mixed $value): void
    {     
        if($this->getisUniqe()){
            $count = $this->engine->countSQl('User', [
                [
                    'column' => $this->getName(),
                    'value' => $value
                ]
            ]);
            
            if($count >= 1){
                throw new ModelValidateException('This field is isUniqe !');
            }

        }

        if(!$this->isNull() && $value === null){
            throw new ModelValidateException('This field cannot not be null !');
        }

        if($value !== null){
            if (strlen($value) > $this->getLength()){
                throw new ModelValidateException('This value '.strlen($value).' is to length max length '.$this->getLength().'!');
            }
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/@.+\./', $value)) {
            throw new ModelValidateException('This value is not emial !');
        }
    }
}

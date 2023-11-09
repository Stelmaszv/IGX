<?php

namespace App\Core\Model;

trait FieldValidate
{
    public function validate(mixed $value): void
    {
        if(!$this->isNull() && $value === null){
            throw new ModelValidateException('This field cannot not be null !');
        }

        if($value !== null){
            if (strlen($value) > $this->getLength()){
                throw new ModelValidateException('This value '.strlen($value).' is to length max length '.$this->getLength().'!');
            }
        }
    }
}
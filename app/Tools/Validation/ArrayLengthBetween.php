<?php

namespace Core\Tools\Validation;

use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation;

class ArrayLengthBetween extends Validator implements ValidatorInterface
{

    /**
     * Executes the validation
     *
     * @param mixed $validation
     * @param string $field
     * @return bool
     */
    public function validate(Validation $validation, $field)
    {
        $array = $validation->getValue($field);

        if (is_array($array)) {
            $length = count($array);

            $maxLength = $this->getOption('max');
            $minLength = $this->getOption('min');

            if ($maxLength < $minLength) {
                $tmp = $maxLength;
                $maxLength = $minLength;
                $minLength = $tmp;
            }

            if ($length <= $maxLength && $length >= $minLength) {
                return TRUE;
            } else {
                $message = $this->getOption('message');
                if (!$message) {
                    $message = sprintf("字段{%s}的长度必须在%d ~ %d之间", $field, $minLength, $maxLength);
                }
                $validation->appendMessage(new Message($message, $field, 'token'));
                return FALSE;
            }

        } else {
            return TRUE;
        }
    }
}
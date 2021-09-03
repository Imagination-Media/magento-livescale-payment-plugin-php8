<?php
/**
 * Copyright © 2021 Livescale. All rights reserved.
 * See LICENSE for license details.
 */
namespace Livescale\Payment\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;

class ResponseCodeValidator extends AbstractValidator
{
    /**
     * Performs validation
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        return $this->createResult(true,[]);
    }
}

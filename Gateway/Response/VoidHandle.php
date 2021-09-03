<?php
/**
 * Copyright © 2021 Livescale. All rights reserved.
 * See LICENSE for license details.
 */
namespace Livescale\Payment\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Model\Method\Logger;

class VoidHandle implements HandlerInterface
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        Logger $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * Handles transaction id
     *
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response)
    {    
      if (!isset($handlingSubject['payment'])
          || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface
      ) {
          throw new \InvalidArgumentException('Payment data object should be provided');
      }
    }
}

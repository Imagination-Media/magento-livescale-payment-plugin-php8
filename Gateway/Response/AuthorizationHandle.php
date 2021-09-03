<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Livescale\Payment\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Model\Method\Logger;

class AuthorizationHandle implements HandlerInterface
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

        /** @var PaymentDataObjectInterface $paymentDO */
        $paymentDO = $handlingSubject['payment'];

        $payment = $paymentDO->getPayment();

        $transactionId = $payment->getAdditionalInformation('gatewayTransactionId');
        $ccExpirationMonth = $payment->setAdditionalInformation('ccExpirationMonth');
        $ccExpirationYear = $payment->setAdditionalInformation('ccExpirationYear');
        $ccLast4 = $payment->setAdditionalInformation('ccLast4');
        $ccHolder = $payment->setAdditionalInformation('ccHolder');

        $this->logger->debug(['ccExpirationMonth' => $ccExpirationMonth]);

        /** @var $payment \Magento\Sales\Model\Order\Payment */
        $payment->setTransactionId($transactionId);
        $payment->setCcExpMonth($ccExpirationMonth);
        $payment->setCcExpYear($ccExpirationYear);
        $payment->setCcLast4($ccLast4);
        $payment->setCcOwner($ccHolder);
        $payment->setIsTransactionClosed(false);
    }
}

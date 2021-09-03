<?php
/**
 * Copyright © 2021 Livescale. All rights reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Livescale\Payment\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;

use Magento\Payment\Model\Method\Logger;

/**
 * @inheritdoc
 */
class SetGatewayTransaction implements ResolverInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @param GetCartForUser $getCartForUser
     */
    public function __construct(
        Logger $logger,
        GetCartForUser $getCartForUser
    ) {
        $this->logger = $logger;
        $this->getCartForUser = $getCartForUser;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['input']['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }

        if (empty($args['input']['gateway_transaction_id'])) {
            throw new GraphQlInputException(__('Required parameter "gateway_transaction_id" is missing'));
        }

        $maskedCartId = $args['input']['cart_id'];
        $gatewayTransactionId = $args['input']['gateway_transaction_id'];

        $currentUserId = $context->getUserId();
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $currentUserId, $storeId);

        try {
            $payment = $cart->getPayment();

            $payment->setAdditionalInformation('gatewayTransactionId', $gatewayTransactionId);

            if (isset($args['input']['cc_type'])) {
                $ccType = $args['input']['cc_type'];
                $payment->setAdditionalInformation('ccType', $ccType);
            }

            if (isset($args['input']['cc_expiration_month'])) {
                $ccExpirationMonth = $args['input']['cc_expiration_month'];
                $payment->setAdditionalInformation('ccExpirationMonth', $ccExpirationMonth);
            }

            if (isset($args['input']['cc_expiration_year'])) {
                $ccExpirationYear = $args['input']['cc_expiration_year'];
                $payment->setAdditionalInformation('ccExpirationYear', $ccExpirationYear);
            }
            
            if (isset($args['input']['cc_last_4'])) {
                $ccLast4 = $args['input']['cc_last_4'];
                $payment->setAdditionalInformation('ccLast4', $ccLast4);
            }

            if (isset($args['input']['cc_holder'])) {
                $ccHolder = $args['input']['cc_holder'];
                $payment->setAdditionalInformation('ccHolder', $ccHolder);
            }

            $payment->save();

            return [
                'cart' => [
                    'model' => $cart,
                ],
            ];
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__('Unable to place order: %message', ['message' => $e->getMessage()]), $e);
        }
    }
}
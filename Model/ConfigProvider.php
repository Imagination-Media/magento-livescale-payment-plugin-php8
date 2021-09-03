<?php
/**
 * Copyright © 2021 Livescale. All rights reserved.
 * See LICENSE for license details.
 */
namespace Livescale\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const SUCCESS = 1;
    const FAILURE = 0;
    const CODE = 'livescale_payment';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        self::SUCCESS => __('Success'),
                        self::FAILURE => __('Failed')
                    ]
                ]
            ]
        ];
    }
}

<?php

namespace craft\commerce\gateways;

use Craft;
use craft\commerce\gateways\base\CreditCardGateway;
use craft\commerce\models\payments\StripePaymentForm;
use Omnipay\Stripe\Gateway;

/**
 * MissingGateway represents a payment method with an invalid class.
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @copyright Copyright (c) 2017, Pixel & Tonic, Inc.
 * @license   https://craftcommerce.com/license Craft Commerce License Agreement
 * @see       https://craftcommerce.com
 * @package   craft.commerce
 * @since     2.0
 */
class Stripe extends CreditCardGateway
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    public $publishableKey;

    /**
     * @var string
     */
    public $apiKey;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('commerce', 'Stripe gateway');
    }

    /**
     * @inheritdoc
     */
    protected function getGatewayClassName()
    {
        return Gateway::class;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('commerce/_components/gateways/Stripe/settings',
            [
                'gateway' => $this,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormHtml(array $params)
    {
        $defaults = [
            'paymentMethod' => $this,
            'paymentForm' => $this->getPaymentFormModel()
        ];

        $params = array_merge($defaults, $params);

        Craft::$app->getView()->registerJsFile('https://js.stripe.com/v2/');

        return Craft::$app->getView()->render('commerce/_components/gateways/Stripe/paymentForm', $params);
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormModel()
    {
        return new StripePaymentForm();
    }

    /**
     * @inheritdoc
     */
    public function supportsRefund()
    {
        return true;
    }
}

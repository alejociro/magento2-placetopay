<?php

namespace PlacetoPay\Payments\Helper;

use Magento\Framework\App\Config\Initial;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\LayoutFactory;
use Magento\Payment\Helper\Data as BaseData;
use Magento\Payment\Model\Config;
use Magento\Payment\Model\Method\Factory;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\ScopeInterface;
use PlacetoPay\Payments\Logger\Logger;
use PlacetoPay\Payments\Model\Adminhtml\Source\Country;
use PlacetoPay\Payments\Model\Adminhtml\Source\Mode;

/**
 * Class Data.
 */
class Data extends BaseData
{
    /**
     * @var Logger
     */
    protected $_placeToPayLogger;

    /**
     * @var string
     */
    protected $_mode;

    /**
     * Data constructor.
     *
     * @param Logger $placeToPayLogger
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     * @param Factory $paymentMethodFactory
     * @param Emulation $appEmulation
     * @param Config $paymentConfig
     * @param Initial $initialConfig
     */
    public function __construct(
        Logger $placeToPayLogger,
        Context $context,
        LayoutFactory $layoutFactory,
        Factory $paymentMethodFactory,
        Emulation $appEmulation,
        Config $paymentConfig,
        Initial $initialConfig
    ) {
        parent::__construct(
            $context,
            $layoutFactory,
            $paymentMethodFactory,
            $appEmulation,
            $paymentConfig,
            $initialConfig
        );

        $this->_placeToPayLogger = $placeToPayLogger;

        $this->_mode = $this->scopeConfig->getValue(
            'payment/placetopay/placetopay_mode',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/merchant_id',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getLegalName()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/legal_name',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/email',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/phone',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getExpirationTime()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/expiration',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getFinalPage()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/final_page',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getAllowPendingPayment(): bool
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/allow_pending_payment',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getAllowPartialPayment(): bool
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/allow_partial_payment',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getHasCifin(): bool
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/has_cifin',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getFillTaxInformation(): bool
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/fill_tax_information',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getFillBuyerInformation(): bool
    {
        return ! $this->scopeConfig->getValue(
            'payment/placetopay/fill_buyer_information',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getSkipResult(): bool
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/skip_result',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getPaymentMethods()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/payment_methods',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getMinimumAmount()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/minimum_amount',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getMaximumAmount()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/maximum_amount',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getTaxRateParsing()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/tax_rate_parsing',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getEmailSuccessOption()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/email_success',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/active',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getTile()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/title',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->scopeConfig->getValue(
            'payment/placetopay/description',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed|string
     */
    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * @return string|null
     */
    public function getTranKey()
    {
        $tranKey = null;

        switch ($this->_mode) {
            case Mode::DEVELOPMENT:
                $tranKey = $this->scopeConfig->getValue(
                    'payment/placetopay/placetopay_development_tk',
                    ScopeInterface::SCOPE_STORE
                );
                break;
            case Mode::TEST:
                $tranKey = $this->scopeConfig->getValue(
                    'payment/placetopay/placetopay_test_tk',
                    ScopeInterface::SCOPE_STORE
                );
                break;
            default:
                $tranKey = $this->scopeConfig->getValue(
                    'payment/placetopay/placetopay_production_tk',
                    ScopeInterface::SCOPE_STORE
                );
                break;
        }

        return $tranKey;
    }

    /**
     * @return string|null
     */
    public function getLogin()
    {
        $login = null;

        switch ($this->_mode) {
            case Mode::DEVELOPMENT:
                $login = $this->scopeConfig->getValue(
                    'payment/placetopay/placetopay_development_lg',
                    ScopeInterface::SCOPE_STORE
                );
                break;
            case Mode::TEST:
                $login = $this->scopeConfig->getValue(
                    'payment/placetopay/placetopay_test_lg',
                    ScopeInterface::SCOPE_STORE
                );
                break;
            default:
                $login = $this->scopeConfig->getValue(
                    'payment/placetopay/placetopay_production_lg',
                    ScopeInterface::SCOPE_STORE
                );
                break;
        }

        return $login;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        $country = $this->scopeConfig->getValue(
            'payment/placetopay/country',
            ScopeInterface::SCOPE_STORE
        );

        return $country;
    }

    /**
     * @return array
     */
    public static function getDefaultEndpoints()
    {
        return [
            Mode::DEVELOPMENT => 'https://dev.placetopay.com/redirection',
            Mode::TEST => 'https://test.placetopay.com/redirection',
            Mode::PRODUCTION => 'https://secure.placetopay.com/redirection',
        ];
    }

    /**
     * @param $countryCode
     *
     * @return array
     */
    public function getEndpointsTo($countryCode)
    {
        switch ($countryCode) {
            case Country::ECUADOR:
                $endpoints = [
                    Mode::DEVELOPMENT => 'https://dev.placetopay.ec/redirection',
                    Mode::TEST => 'https://test.placetopay.ec/redirection',
                    Mode::PRODUCTION => 'https://secure.placetopay.ec/redirection',
                ];
                break;
            case Country::COLOMBIA:
            default:
                $endpoints = self::getDefaultEndpoints();
                break;
        }

        return $endpoints;
    }
}

<?php

namespace PlacetoPay\Payments\Block\Frontend\Onepage;

use Magento\Checkout\Model\Session;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class Pending.
 */
class Pending extends Template
{
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var TimezoneInterface
     */
    protected $_timezoneInterface;

    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * Pending constructor.
     *
     * @param Context $context
     * @param Session $checkoutSession
     * @param OrderRepositoryInterface $orderRepository
     * @param TimezoneInterface $timezoneInterface
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        TimezoneInterface $timezoneInterface,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_orderRepository = $orderRepository;
        $this->_timezoneInterface = $timezoneInterface;
        $this->_priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * @return mixed
     */
    public function getRealOrderId()
    {
        return $this->_checkoutSession->getLastRealOrderId();
    }

    /**
     *  Payment custom error message
     *
     * @return string
     */
    public function getWarningMessage()
    {
        return $this->_checkoutSession->getWarningMessage();
    }

    /**
     * Continue shopping URL
     *
     * @return string
     */
    public function getContinueShoppingUrl()
    {
        return $this->getUrl('checkout/cart');
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getOrder()
    {
        return $this->_orderRepository->get($this->getRealOrderId());
    }

    /**
     * @param $date
     * @param string $format
     * @return string
     */
    public function dateFormat($date, $format = 'd F Y')
    {
        return $this->_timezoneInterface->date($date)->format($format);
    }

    /**
     * @param $amount
     * @return string
     */
    public function getFormattedPrice($amount)
    {
        return $this->_priceCurrency->convertAndFormat($amount);
    }
}

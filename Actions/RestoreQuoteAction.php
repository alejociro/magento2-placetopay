<?php

namespace PlacetoPay\Payments\Actions;

use Magento\Checkout\Model\Session;
use Magento\Sales\Model\Order;

abstract class RestoreQuoteAction
{
    public static function execute(Session $session, Order $order): bool
    {
        if ((int) $session->getLastOrderId() !== (int) $order->getId()
            || (string) $session->getLastRealOrderId() !== (string) $order->getIncrementId()
        ) {
            return false;
        }

        $restored = $session->restoreQuote();

        SetOrderInfoSession::withQouteId($session, $order);

        return $restored;
    }
}

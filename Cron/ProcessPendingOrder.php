<?php

namespace PlacetoPay\Payments\Cron;

use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use PlacetoPay\Payments\Constants\PaymentStatus;
use PlacetoPay\Payments\Logger\Logger as LoggerInterface;
use PlacetoPay\Payments\Model\PaymentMethod;

/**
 * Class ProcessPendingOrder.
 */
class ProcessPendingOrder
{

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    protected $placetopay;

    private $logger;

    /**
     * ProcessPendingOrder constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param PaymentMethod     $placetopay
     */
    public function __construct(
        LoggerInterface $logger,
        CollectionFactory $collectionFactory,
        PaymentMethod $placetopay
    ) {
        $this->logger = $logger;
        $this->collectionFactory = $collectionFactory;
        $this->placetopay = $placetopay;
    }

    /**
     * @throws PlacetoPayException
     * @throws LocalizedException
     */
    public function execute(): void
    {
        /** @var Order $orders */
        $orders = $this->collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('state', ['in' => [
              Order::STATE_PENDING_PAYMENT,
              Order::STATE_NEW,
            ]])
            ->addAttributeToFilter('status', ['in' => [
                PaymentStatus::PENDING_PAYMENT, PaymentStatus::PENDING
            ]])->addAttributeToSort('entity_id');

        if ($orders) {
            foreach ($orders as $order) {
                $this->logger->debug('Process order pending' . $order->getRealOrderId());
                $information = $order->getPayment()->getAdditionalInformation();
                if (empty($information['request_id'])) {
                    $this->placetopay->processPendingOrderFail($order);
                } else {
                    $requestId = $information['request_id'];
                    $statusPayment = $order->getPayment()->getAdditionalInformation()['status'];
                    $this->logger->debug('status ' . $statusPayment);
                    if (!in_array($statusPayment, [PaymentStatus::APPROVED,PaymentStatus::REJECTED])) {
                        $this->logger->debug('ProcessPendingOrder', ['Request:' => $requestId]);
                        $this->placetopay->processPendingOrder($order, $requestId);
                    }
                }
            }
        }
    }
}

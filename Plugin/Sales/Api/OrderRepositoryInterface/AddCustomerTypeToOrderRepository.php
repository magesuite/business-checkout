<?php
namespace MageSuite\BusinessCheckout\Plugin\Sales\Api\OrderRepositoryInterface;

class AddCustomerTypeToOrderRepository
{
    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    protected $orderExtensionFactory;

    /**
     * @var \Magento\Sales\Api\Data\OrderItemExtensionFactory
     */
    protected $orderItemExtensionFactory;

    public function __construct(
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory,
        \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory
    )
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    }


    public function afterGet(\Magento\Sales\Api\OrderRepositoryInterface $subject, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $customerType = $order->getData(\MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE);

        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();

        $extensionAttributes->setCustomerType($customerType);

        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }


    public function afterGetList(\Magento\Sales\Api\OrderRepositoryInterface $subject, \Magento\Sales\Api\Data\OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            $customerType = $order->getData(\MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE);

            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();

            $extensionAttributes->setCustomerType($customerType);

            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}

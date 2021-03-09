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

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $addressRepository;

    public function __construct(
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory,
        \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
    )
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
        $this->addressRepository = $addressRepository;
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

    public function afterSave(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Api\Data\OrderInterface $result,
        \Magento\Sales\Api\Data\OrderInterface $entity
    ) {
        if (!$result->getCustomerId()) {
            return $result;
        }

        $shippingAddress = $result->getShippingAddress();

        if (!$shippingAddress) {
            return $result;
        }

        $orderCustomerType = $result->getData(\MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE);

        if (!$orderCustomerType) {
            return $result;
        }

        $addressId = $shippingAddress->getCustomerAddressId();

        try {
            $address = $this->addressRepository->getById($addressId);
            $customerType = $address->getCustomAttribute(\MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE);

            if (!$customerType) {
                $address->setCustomAttribute(
                    \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE,
                    $orderCustomerType
                );
                $this->addressRepository->save($address);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // do nothing
        }

        return $result;
    }
}

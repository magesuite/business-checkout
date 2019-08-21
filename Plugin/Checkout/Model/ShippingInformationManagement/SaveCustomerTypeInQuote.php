<?php
namespace MageSuite\BusinessCheckout\Plugin\Checkout\Model\ShippingInformationManagement;

class SaveCustomerTypeInQuote
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    public function __construct(\Magento\Quote\Api\CartRepositoryInterface $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
    {
        $shippingAddress = $addressInformation->getShippingAddress();

        if(empty($shippingAddress->getExtensionAttributes())){
            return [$cartId, $addressInformation];
        }

        $customerType = $shippingAddress->getExtensionAttributes()->getCustomerType();

        if(empty($customerType)){
            return [$cartId, $addressInformation];
        }

        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setCustomerType($customerType);

        return [$cartId, $addressInformation];
    }
}
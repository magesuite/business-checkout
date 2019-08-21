<?php

namespace MageSuite\BusinessCheckout\Block\Address\Edit\Field;

class CustomerType extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType
     */
    protected $customerTypeSource;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterface
     */
    protected $address;

    protected $_template = 'address/edit/field/customer_type.phtml';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType $customerTypeSource,
        array $data = []
    ){
        parent::__construct($context, $data);

        $this->customerTypeSource = $customerTypeSource;
    }

    public function getOptions()
    {
        return $this->customerTypeSource->getAllOptions();
    }

    public function getCustomerTypeValue()
    {
        $address = $this->getAddress();
        $customerType = $address->getCustomAttribute(\MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE);

        if (!$customerType instanceof \Magento\Framework\Api\AttributeInterface) {
            return null;
        }

        return $customerType->getValue();
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }
}

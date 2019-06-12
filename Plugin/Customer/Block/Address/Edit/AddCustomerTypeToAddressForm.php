<?php

namespace MageSuite\BusinessCheckout\Plugin\Customer\Block\Address\Edit;

class AddCustomerTypeToAddressForm
{
    /**
     * @var \Magento\Framework\View\Element\Template
     */
    protected $template;

    public function __construct(\Magento\Framework\View\Element\Template $template)
    {
        $this->template = $template;
    }

    public function afterGetNameBlockHtml(\Magento\Customer\Block\Address\Edit $subject, $result)
    {
        $customerTypeBlockHtml = $this->getCustomerTypeBlockHtml($subject);

        if(empty($customerTypeBlockHtml)){
            return $result;
        }

        return $customerTypeBlockHtml . $result;
    }

    protected function getCustomerTypeBlockHtml(\Magento\Customer\Block\Address\Edit $subject)
    {
        $customerTypeBlock = $subject
            ->getLayout()
            ->createBlock(\MageSuite\BusinessCheckout\Block\Address\Edit\Field\CustomerType::class);

        if(empty($customerTypeBlock)){
            return false;
        }

        $customerTypeBlock->setAddress($subject->getAddress());

        return $customerTypeBlock->toHtml();
    }
}
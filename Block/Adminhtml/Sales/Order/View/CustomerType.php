<?php
namespace MageSuite\BusinessCheckout\Block\Adminhtml\Sales\Order\View;

class CustomerType extends \Magento\Sales\Block\Adminhtml\Order\AbstractOrder
{
    /**
     * @var \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType
     */
    protected $customerTypeSource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType $customerTypeSource,
        array $data = []
    ){
        parent::__construct($context, $registry, $adminHelper, $data);

        $this->customerTypeSource = $customerTypeSource;
    }

    public function getCustomerType()
    {
        $customerTypeCode = $this->getOrder()->getCustomerType();

        if(empty($customerTypeCode)){
            return null;
        }

        $options = $this->customerTypeSource->getAllOptions();

        foreach ($options as $option){
            if($option['value'] != $customerTypeCode){
                continue;
            }

            return $option['label'];
        }

        return null;
    }
}
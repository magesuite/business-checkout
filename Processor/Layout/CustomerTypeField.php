<?php

namespace MageSuite\BusinessCheckout\Processor\Layout;

class CustomerTypeField extends \Magento\Checkout\Model\Layout\AbstractTotalsProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @var \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType
     */
    protected $customerTypeSource;

    /**
     * @var \MageSuite\BusinessCheckout\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType $customerTypeSource,
        \MageSuite\BusinessCheckout\Helper\Configuration $configuration
    )
    {
        parent::__construct($scopeConfig);

        $this->customerTypeSource = $customerTypeSource;
        $this->configuration = $configuration;
    }

    public function process($jsLayout)
    {
        if(!$this->configuration->isEnabled()){
            return $jsLayout;
        }

        $customField = $this->getExtensionAttributeFieldAsArray();

        $newJsLayout = [
            'components' => [
                'checkout' => [
                    'children' => [
                        'steps' => [
                            'children' => [
                                'shipping-step' => [
                                    'children' => [
                                        'shippingAddress' => [
                                            'children' => [
                                                'shipping-address-fieldset' => [
                                                    'children' => [
                                                        \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::ATTRIBUTE_CODE => $customField
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $jsLayout = array_merge_recursive($jsLayout, $newJsLayout);
        return $jsLayout;
    }

    protected function getExtensionAttributeFieldAsArray()
    {
        $extensionAttributeField = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.' . \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::ATTRIBUTE_CODE,
            'label' => 'Customer Type',
            'provider' => 'checkoutProvider',
            'sortOrder' => 5,
            'validation' => [
                'required-entry' => false
            ],
            'options' => $this->customerTypeSource->getAllOptions(),
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
        ];

        return $extensionAttributeField;
    }
}
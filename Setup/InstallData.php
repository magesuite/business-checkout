<?php

namespace MageSuite\BusinessCheckout\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $eavSetup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected $moduleDataSetupInterface;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $customerSetupFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetupInterface,
        \Magento\Eav\Model\Config $eavConfig
    )
    {
        $this->eavSetupFactory = $customerSetupFactory;
        $this->moduleDataSetupInterface = $moduleDataSetupInterface;
        $this->eavConfig = $eavConfig;

        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetupInterface]);
    }

    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        if (!$this->eavSetup->getAttributeId(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS, \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE)) {
            $this->eavSetup->addAttribute(
                \Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE,
                [
                    'group' => 'General',
                    'type' => 'varchar',
                    'label' => 'Customer Type',
                    'input' => 'select',
                    'source' => \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::class,
                    'required' => false,
                    'visible' => true,
                    'system' => false,
                    'sort_order' => 10,
                    'default' => \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::PRIVATE,
                    'validate_rules' => null,
                    'position' => 5,
                    'user_defined' => true
                ]
            );

            $attribute = $this->eavConfig->getAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS, \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE);
            $attribute->setData(
                'used_in_forms',
                [
                    'adminhtml_customer_address',
                    'customer_address_edit',
                    'customer_register_address'
                ]
            );
            $attribute->save();
        }
    }
}

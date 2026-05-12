<?php

declare(strict_types=1);

namespace MageSuite\BusinessCheckout\Setup\Patch\Data;

class AddCustomerTypeAddressAttribute implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    protected \Magento\Eav\Setup\EavSetup $eavSetup;

    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        protected \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->eavSetup = $eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
    }

    public function apply(): self
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $entityType = \Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS;
        $attributeCode = \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE;

        if (!$this->eavSetup->getAttributeId($entityType, $attributeCode)) {
            $this->eavSetup->addAttribute(
                $entityType,
                $attributeCode,
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
                    'user_defined' => true,
                ]
            );

            $attribute = $this->eavConfig->getAttribute($entityType, $attributeCode);
            $attribute->setData('used_in_forms', [
                'adminhtml_customer_address',
                'customer_address_edit',
                'customer_register_address',
            ]);
            $attribute->save();
        }

        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}

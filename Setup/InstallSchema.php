<?php

namespace MageSuite\BusinessCheckout\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (!$setup->getConnection()->tableColumnExists($setup->getTable('quote'), \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE)) {

            $setup->getConnection()->addColumn(
                $setup->getTable('quote'),
                \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE,
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'nullable' => true,
                    'comment' => 'Customer Type'
                ]
            );
        }

        if (!$setup->getConnection()->tableColumnExists($setup->getTable('sales_order'), \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE)) {

            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order'),
                \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE,
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'nullable' => true,
                    'comment' => 'Customer Type'
                ]
            );
        }

        $setup->endSetup();
    }
}

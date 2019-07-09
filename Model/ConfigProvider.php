<?php
namespace MageSuite\BusinessCheckout\Model;

class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @var \MageSuite\BusinessCheckout\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BusinessCheckout\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfig()
    {
        $businessCheckoutConfig = [];

        $businessCheckoutConfig['business_checkout_config'] = $this->configuration->isEnabled();

        return $businessCheckoutConfig;
    }
}

<?php
namespace MageSuite\BusinessCheckout\Observer;

class AddCustomerTypeToOrder implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\BusinessCheckout\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BusinessCheckout\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->configuration->isEnabled()){
            return $this;
        }

        $quote = $observer->getQuote();

        if(empty($quote->getCustomerType())){
            return $this;
        }

        $order = $observer->getOrder();

        $order->setData('customer_type', $quote->getCustomerType());

        return $this;
    }
}

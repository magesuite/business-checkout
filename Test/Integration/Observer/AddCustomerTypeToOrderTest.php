<?php
namespace MageSuite\BusinessCheckout\Test\Integration\Observer;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class AddCustomerTypeToOrderTest extends \PHPUnit\Framework\TestCase
{
    const DEFAULT_STORE_ID = 1;

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Quote\Api\CartManagementInterface
     */
    protected $cartManagement;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \Magento\Quote\Model\QuoteManagement
     */
    protected $quoteManagement;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->storeManager = $this->objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $this->cartManagement = $this->objectManager->get(\Magento\Quote\Api\CartManagementInterface::class);
        $this->cartRepository = $this->objectManager->get(\Magento\Quote\Api\CartRepositoryInterface::class);
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->orderRepository = $this->objectManager->get(\Magento\Sales\Api\OrderRepositoryInterface::class);

    }

    public static function loadProducts()
    {
        require __DIR__ . '/../_files/products.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadProducts
     */
    public function testItDoesntAddCustomerTypeWhenModuleIsDisabled()
    {
        $qty = 1;
        $product = $this->productRepository->get('product');

        $quote = $this->prepareQuote($product, $qty);
        $orderId = $this->cartManagement->placeOrder($quote->getId());

        $order = $this->orderRepository->get($orderId);

        $this->assertEquals(null, $order->getCustomerType());
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoConfigFixture default_store business_checkout/general/is_enabled 1
     * @magentoDataFixture loadProducts
     */
    public function testItAddsCustomerTypeFlagCorrectlyToOrder()
    {
        $qty = 1;
        $product = $this->productRepository->get('product');

        $quote = $this->prepareQuote($product, $qty);
        $orderId = $this->cartManagement->placeOrder($quote->getId());

        $order = $this->orderRepository->get($orderId);

        $this->assertEquals(\MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::BUSINESS, $order->getCustomerType());
    }

    private function prepareQuote($product, $qty)
    {
        $addressData = [
            'region' => 'BE',
            'postcode' => '11111',
            'lastname' => 'lastname',
            'firstname' => 'firstname',
            'street' => 'street',
            'city' => 'Los Angeles',
            'email' => 'admin@example.com',
            'telephone' => '11111111',
            'country_id' => 'DE'
        ];

        $shippingMethod = 'freeshipping_freeshipping';

        $store = $this->storeManager->getStore(self::DEFAULT_STORE_ID);

        $cartId = $this->cartManagement->createEmptyCart();
        $quote = $this->cartRepository->get($cartId);
        $quote->setStore($store);

        $quote->setCustomerEmail('test@example.com');
        $quote->setCustomerIsGuest(true);

        $quote->setCurrency();

        $quote->addProduct($product, intval($qty));

        $billingAddress = $this->objectManager->create('Magento\Quote\Api\Data\AddressInterface', ['data' => $addressData]);
        $billingAddress->setAddressType('billing');

        $shippingAddress = clone $billingAddress;
        $shippingAddress->setId(null)->setAddressType('shipping');

        $rate = $this->objectManager->create(\Magento\Quote\Model\Quote\Address\Rate::class);
        $rate->setCode($shippingMethod);

        $quote->getPayment()->importData(['method' => 'checkmo']);

        $quote->setBillingAddress($billingAddress);
        $quote->setShippingAddress($shippingAddress);
        $quote->getShippingAddress()->addShippingRate($rate);
        $quote->getShippingAddress()->setShippingMethod($shippingMethod);


        $quote->setPaymentMethod('checkmo');
        $quote->setInventoryProcessed(false);

        $quote->save();

        $quote->collectTotals();

        $quote->setCustomerType(\MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::BUSINESS);

        return $quote;
    }
}

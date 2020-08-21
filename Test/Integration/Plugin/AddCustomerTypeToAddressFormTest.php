<?php

namespace MageSuite\BusinessCheckout\Test\Integration\Plugin;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class AddCustomerTypeToAddressFormTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterfaceFactory
     */
    protected $addressFactory;

    /**
     * @var \Magento\Customer\Model\Address
     */
    protected $addressModel;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    protected function setUp(): void
    {
        parent::setUp();

        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->accountManagement = $this->objectManager->create(\Magento\Customer\Api\AccountManagementInterface::class);
        $this->formKey = $this->objectManager->create(\Magento\Framework\Data\Form\FormKey::class);
        $this->customerRegistry = $this->objectManager->create(\Magento\Customer\Model\CustomerRegistry::class);

        $this->addressFactory = $this->objectManager->create(\Magento\Customer\Api\Data\AddressInterfaceFactory::class);
        $this->addressModel = $this->objectManager->create(\Magento\Customer\Model\Address::class);

        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $this->session = $this->objectManager->create(\Magento\Customer\Model\Session::class, [$logger]);
    }

    /**
     * @magentoConfigFixture default_store business_checkout/general/is_enabled 1
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_address.php
     */
    public function testFormHasCustomerTypeBlock()
    {
        $customer = $this->accountManagement->authenticate('customer@example.com', 'password');
        $this->session->setCustomerDataAsLoggedIn($customer);

        $this->dispatch('customer/address/edit');

        $body = $this->getResponse()->getBody();

        $this->assertContains(sprintf('id="%s"', \MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE), $body);
        $this->assertContains(sprintf('value="%s"', \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::BUSINESS), $body);
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer_sample.php
     */
    public function testCustomerTypeIsAddedToCustomerAddress()
    {
        $updatedAddressData = $this->addressFactory->create()
            ->setId(1)
            ->setCustomerId($this->customerRegistry->retrieveByEmail('customer@example.com')->getId())
            ->setCustomAttribute(\MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE, \MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::BUSINESS);

        $updatedAddressData = $this->addressModel->updateData($updatedAddressData)->getDataModel();
        $customerTypeAttribute = $updatedAddressData->getCustomAttribute(\MageSuite\BusinessCheckout\Helper\Configuration::CUSTOMER_TYPE_ATTRIBUTE);

        $this->assertEquals(\MageSuite\BusinessCheckout\Model\Entity\Attribute\Source\CustomerType::BUSINESS, $customerTypeAttribute->getValue());
    }


}

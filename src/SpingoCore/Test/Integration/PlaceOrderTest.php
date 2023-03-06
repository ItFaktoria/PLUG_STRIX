<?php

declare(strict_types=1);

namespace IntegerNet\CheckoutTest\Test\Integration;

use Exception;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\GuestShippingInformationManagementInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\GuestCartItemRepositoryInterface;
use Magento\Quote\Api\GuestCartManagementInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\TestCase\AbstractController;
use Spingo\SpingoCore\Client\SpingoApiClient;

class PlaceOrderTest extends AbstractController
{
    /**
     * @var GuestCartManagementInterface
     */
    private $guestCartManagement;

    /**
     * @var GuestCartItemRepositoryInterface
     */
    private $guestCartItemRepository;

    /**
     * @var GuestShippingInformationManagementInterface
     */
    private $guestShippingInformationManagement;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guestCartManagement = $this->_objectManager->get(GuestCartManagementInterface::class);
        $this->guestCartItemRepository = $this->_objectManager->get(GuestCartItemRepositoryInterface::class);
        $this->guestShippingInformationManagement = $this->_objectManager->get(
            GuestShippingInformationManagementInterface::class
        );
        $this->_objectManager->removeSharedInstance(SpingoApiClient::class);
        $spingoApiClient = $this->createMock(SpingoApiClient::class);
        $spingoApiClient->method('submit')->willReturn(
            json_encode(['applicationNumber' => 'test_1234', 'fieldInfos' => ['test_url']])
        );
        $this->_objectManager->addSharedInstance($spingoApiClient, SpingoApiClient::class);
    }

    /**
     * @magentoConfigFixture default/payment/spingo_payment/active 1
     * @magentoConfigFixture default/payment/spingo_payment/model SpingoPayment
     * @magentoConfigFixture default/payment/spingo_payment/payment_action authorize
     * @magentoConfigFixture default/payment/spingo_payment/title Spingo - defer payment
     * @magentoConfigFixture default/payment/spingo_payment/can_authorize 1
     * @magentoConfigFixture default/payment/spingo_payment/can_use_checkout 1
     * @magentoConfigFixture default/payment/spingo_payment/is_gateway 1
     * @magentoConfigFixture default/payment/spingo_payment/sort_order 1
     * @magentoConfigFixture default/payment/spingo_payment/paymentInfoKeys applicationNumber
     * @magentoConfigFixture default/spingo_payment/general/is_active 1
     * @magentoConfigFixture default/spingo_payment/cart/min_order_total 0
     * @magentoConfigFixture default/spingo_payment/cart/max_order_total 30000
     * @magentoConfigFixture default/currency/options/allow PLN
     * @magentoConfigFixture default/currency/options/base PLN
     * @magentoConfigFixture default_store currency/options/allow PLN
     * @magentoConfigFixture default_store currency/options/base PLN
     * @magentoDataFixture Magento/Checkout/_files/products.php
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function testPlaceOrder(): void
    {
        $cartId = $this->getNewCartId();
        $this->addProductToCart($cartId);
        $this->assignShippingInformation($cartId);
        $payment = $this->getPayment();
        $orderId = (int)$this->guestCartManagement->placeOrder($cartId, $payment);
        $this->assertGreaterThanOrEqual(1, $orderId);
        $order = $this->getOrder($orderId);
        $payment = $order->getPayment();
        $this->assertEquals(15, $order->getGrandTotal());
        $this->assertEquals('spingo_payment', $payment->getMethod());
        $this->assertEquals(
            [
                'method_title' => 'Spingo - defer payment',
                'FKT_FPAY_IN_URL.URL_APPLICATION' => 'test_url',
                'applicationNumber' => 'test_1234'
            ],
            $payment->getAdditionalInformation()
        );
    }

    /**
     * @magentoConfigFixture default/payment/spingo_payment/active 1
     * @magentoConfigFixture default/payment/spingo_payment/model SpingoPayment
     * @magentoConfigFixture default/payment/spingo_payment/payment_action authorize
     * @magentoConfigFixture default/payment/spingo_payment/title Spingo - defer payment
     * @magentoConfigFixture default/payment/spingo_payment/can_authorize 1
     * @magentoConfigFixture default/payment/spingo_payment/can_use_checkout 1
     * @magentoConfigFixture default/payment/spingo_payment/is_gateway 1
     * @magentoConfigFixture default/payment/spingo_payment/sort_order 1
     * @magentoConfigFixture default/payment/spingo_payment/paymentInfoKeys applicationNumber
     * @magentoConfigFixture default/spingo_payment/general/is_active 1
     * @magentoConfigFixture default/spingo_payment/cart/min_order_total 500
     * @magentoConfigFixture default/spingo_payment/cart/max_order_total 30000
     * @magentoConfigFixture default/currency/options/allow PLN
     * @magentoConfigFixture default/currency/options/base PLN
     * @magentoConfigFixture default_store currency/options/allow PLN
     * @magentoConfigFixture default_store currency/options/base PLN
     * @magentoDataFixture Magento/Checkout/_files/products.php
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function testPlaceOrderWithoutAllowedMinMaxOrderTotal(): void
    {
        $this->expectExceptionObject(new Exception());
        $this->expectExceptionMessage('The requested Payment Method is not available.');
        $cartId = $this->getNewCartId();
        $this->addProductToCart($cartId);
        $this->assignShippingInformation($cartId);
        $payment = $this->getPayment();
        $this->guestCartManagement->placeOrder($cartId, $payment);
    }

    /**
     * @magentoConfigFixture default/payment/spingo_payment/active 1
     * @magentoConfigFixture default/payment/spingo_payment/model SpingoPayment
     * @magentoConfigFixture default/payment/spingo_payment/payment_action authorize
     * @magentoConfigFixture default/payment/spingo_payment/title Spingo - defer payment
     * @magentoConfigFixture default/payment/spingo_payment/can_authorize 1
     * @magentoConfigFixture default/payment/spingo_payment/can_use_checkout 1
     * @magentoConfigFixture default/payment/spingo_payment/is_gateway 1
     * @magentoConfigFixture default/payment/spingo_payment/sort_order 1
     * @magentoConfigFixture default/payment/spingo_payment/paymentInfoKeys applicationNumber
     * @magentoConfigFixture default/spingo_payment/general/is_active 1
     * @magentoConfigFixture default/spingo_payment/cart/min_order_total 0
     * @magentoConfigFixture default/spingo_payment/cart/max_order_total 30000
     * @magentoConfigFixture default/currency/options/allow USD
     * @magentoConfigFixture default/currency/options/base USD
     * @magentoConfigFixture default_store currency/options/allow USD
     * @magentoConfigFixture default_store currency/options/base USD
     * @magentoDataFixture Magento/Checkout/_files/products.php
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function testPlaceOrderWithoutAllowedCurrency(): void
    {
        $this->expectExceptionObject(new Exception());
        $this->expectExceptionMessage('The requested Payment Method is not available.');
        $cartId = $this->getNewCartId();
        $this->addProductToCart($cartId);
        $this->assignShippingInformation($cartId);
        $payment = $this->getPayment();
        $this->guestCartManagement->placeOrder($cartId, $payment);
    }

    /**
     * @magentoConfigFixture default/payment/spingo_payment/active 1
     * @magentoConfigFixture default/payment/spingo_payment/model SpingoPayment
     * @magentoConfigFixture default/payment/spingo_payment/payment_action authorize
     * @magentoConfigFixture default/payment/spingo_payment/title Spingo - defer payment
     * @magentoConfigFixture default/payment/spingo_payment/can_authorize 1
     * @magentoConfigFixture default/payment/spingo_payment/can_use_checkout 1
     * @magentoConfigFixture default/payment/spingo_payment/is_gateway 1
     * @magentoConfigFixture default/payment/spingo_payment/sort_order 1
     * @magentoConfigFixture default/payment/spingo_payment/paymentInfoKeys applicationNumber
     * @magentoConfigFixture default/spingo_payment/general/is_active 1
     * @magentoConfigFixture default/spingo_payment/cart/min_order_total 0
     * @magentoConfigFixture default/spingo_payment/cart/max_order_total 30000
     * @magentoConfigFixture default/currency/options/allow PLN
     * @magentoConfigFixture default/currency/options/base PLN
     * @magentoConfigFixture default_store currency/options/allow PLN
     * @magentoConfigFixture default_store currency/options/base PLN
     * @magentoDataFixture Magento/Checkout/_files/products.php
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function testPlaceOrderWithoutVatId(): void
    {
        $this->expectExceptionObject(new Exception());
        $this->expectExceptionMessage('The requested Payment Method is not available.');
        $cartId = $this->getNewCartId();
        $this->addProductToCart($cartId);
        $this->assignShippingInformation($cartId, false);
        $payment = $this->getPayment();
        $this->guestCartManagement->placeOrder($cartId, $payment);
    }

    /**
     * @throws CouldNotSaveException
     */
    private function getNewCartId(): string
    {
        return $this->guestCartManagement->createEmptyCart();
    }

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws InputException
     */
    private function addProductToCart(string $cartId): void
    {
        /** @var CartItemInterface $cartItem */
        $cartItem = $this->_objectManager->create(CartItemInterface::class);
        $cartItem->setSku('Simple Product 1 sku');
        $cartItem->setQty(1);
        $cartItem->setQuoteId($cartId);
        $this->guestCartItemRepository->save($cartItem);
    }

    private function assignShippingInformation(string $cartId, bool $withVatId = true): void
    {
        /** @var ShippingInformationInterface $shippingInformation */
        $shippingInformation = $this->_objectManager->create(ShippingInformationInterface::class);
        $shippingInformation->setShippingMethodCode('flatrate');
        $shippingInformation->setShippingCarrierCode('flatrate');
        $shippingInformation->setShippingAddress($this->getShippingAddress($withVatId));
        $shippingInformation->setBillingAddress($this->getBillingAddress($withVatId));
        $this->guestShippingInformationManagement->saveAddressInformation($cartId, $shippingInformation);
    }

    private function getShippingAddress(bool $withVatId): QuoteAddressInterface
    {
        /** @var QuoteAddressInterface $shippingAddress */
        $shippingAddress = $this->_objectManager->create(QuoteAddressInterface::class);
        $shippingAddress->setEmail('shipping@test.com');
        $shippingAddress->setTelephone('+1234567890');
        $shippingAddress->setFirstname('Firstname Shipping');
        $shippingAddress->setLastname('Lastname Shipping');
        $shippingAddress->setCompany('Company Shipping');
        $shippingAddress->setStreet('Street Shipping');
        $shippingAddress->setPostcode('12345');
        $shippingAddress->setCity('City Shipping');
        $shippingAddress->setCountryId('PL');
        $shippingAddress->setRegionId(1002);
        if ($withVatId) {
            $shippingAddress->setVatId('123456789');
        }

        return $shippingAddress;
    }

    private function getBillingAddress(bool $withVatId): QuoteAddressInterface
    {
        /** @var QuoteAddressInterface $billingAddress */
        $billingAddress = $this->_objectManager->create(QuoteAddressInterface::class);
        $billingAddress->setEmail('billing@test.com');
        $billingAddress->setTelephone('+1234567890');
        $billingAddress->setFirstname('Firstname Billing');
        $billingAddress->setLastname('Lastname Billing');
        $billingAddress->setCompany('Company Billing');
        $billingAddress->setStreet('Street Billing');
        $billingAddress->setPostcode('12345');
        $billingAddress->setCity('City Billing');
        $billingAddress->setCountryId('PL');
        $billingAddress->setRegionId(1002);
        if ($withVatId) {
            $billingAddress->setVatId('123456789');
        }

        return $billingAddress;
    }

    private function getPayment(): PaymentInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $this->_objectManager->create(PaymentInterface::class);
        $payment->setMethod('spingo_payment');

        return $payment;
    }

    private function getOrder(int $orderId): OrderInterface
    {
        /** @var OrderRepositoryInterface $orderRepository */
        $orderRepository = $this->_objectManager->get(OrderRepositoryInterface::class);

        return $orderRepository->get($orderId);
    }
}

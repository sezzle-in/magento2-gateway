<?php
/*
 * @category    Sezzle
 * @package     Sezzle_Sezzlepay
 * @copyright   Copyright (c) Sezzle (https://www.sezzle.in/)
 */

namespace Sezzle\Sezzlepay\Controller\Standard;

use Sezzle\Sezzlepay\Controller\AbstractController\SezzlePay;

/**
 * Class Redirect
 * @package Sezzle\Sezzlepay\Controller\Standard
 */
class Redirect extends SezzlePay
{
    /**
     * Redirection
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('checkout/cart');
            return $resultRedirect;
        }

        $this->sezzleHelper->logSezzleActions("****Starting Sezzle Checkout****");
        $quote = $this->_checkoutSession->getQuote();
        $this->sezzleHelper->logSezzleActions("Quote Id : " . $quote->getId());

        if ($this->_customerSession->isLoggedIn()) {
            $customerId = $this->_customerSession->getCustomer()->getId();
            $this->sezzleHelper->logSezzleActions("Customer Id : $customerId");
            $customer = $this->_customerRepository->getById($customerId);
            $quote->setCustomer($customer);
        } else {
            $post = $this->getRequest()->getPostValue();
            $this->sezzleHelper->logSezzleActions("Guest customer");
            if (!empty($post['email'])) {
                $quote->setCustomerEmail($post['email'])
                    ->setCustomerIsGuest(true)
                    ->setCustomerGroupId(\Magento\Customer\Api\Data\GroupInterface::NOT_LOGGED_IN_ID);
            }
        }

        $billingAddress = $quote->getBillingAddress();
        $shippingAddress = $quote->getShippingAddress();
        if ((empty($shippingAddress) || empty($shippingAddress->getStreetLine(1))) && (empty($billingAddress) || empty($billingAddress->getStreetLine(1)))) {
            $json = $this->_jsonHelper->jsonEncode(["message" => "Please select an address"]);
            $jsonResult = $this->_resultJsonFactory->create();
            $jsonResult->setData($json);
            return $jsonResult;
        } elseif (empty($billingAddress) || empty($billingAddress->getStreetLine(1)) || empty($billingAddress->getFirstname())) {
            $quote->setBillingAddress($shippingAddress);
        }

        $payment = $quote->getPayment();
        $payment->setMethod('sezzlepay');
        $quote->reserveOrderId();
        $quote->setPayment($payment);
        $quote->save();
        $this->_checkoutSession->replaceQuote($quote);
        $checkoutUrl = $this->_sezzlepayModel->getSezzleCheckoutUrl($quote);
        $this->sezzleHelper->logSezzleActions("Checkout Url : $checkoutUrl");
        $json = $this->_jsonHelper->jsonEncode(["redirectURL" => $checkoutUrl]);
        $jsonResult = $this->_resultJsonFactory->create();
        $jsonResult->setData($json);
        return $jsonResult;
    }
}

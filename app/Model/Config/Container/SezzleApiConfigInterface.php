<?php
/*
 * @category    Sezzle
 * @package     Sezzle_Sezzlepay
 * @copyright   Copyright (c) Sezzle (https://www.sezzle.in/)
 */

namespace Sezzle\Sezzlepay\Model\Config\Container;

use Magento\Store\Model\Store;

/**
 * Interface IdentityInterface
 * @package Sezzle\Sezzlepay\Model\Config\Container
 */
interface SezzleApiConfigInterface extends IdentityInterface
{

    /**
     * Get public key
     * @return mixed
     */
    public function getPublicKey();

    /**
     * Get private key
     * @return mixed
     */
    public function getPrivateKey();

    /**
     * Get Payment mode
     * @return mixed
     */
    public function getPaymentMode();

    /**
     * Get Merchant Id
     * @return mixed
     */
    public function getMerchantId();

    /**
     * Get Sezzle base url
     * @return mixed
     */
    public function getSezzleBaseUrl();

    /**
     * Get log tracker status
     * @return mixed
     */
    public function isLogTrackerEnabled();

    /**
     * Get payment action
     * @return mixed
     */
    public function getPaymentAction();

    /**
     * Get min checkout amount
     * @return mixed
     */
    public function getMinCheckoutAmount();

    /**
     * Get widget script status for PDP
     * @return mixed
     */
    public function isWidgetScriptAllowedForPDP();

    /**
     * Get widget script status for cart page
     * @return mixed
     */
    public function isWidgetScriptAllowedForCartPage();
}

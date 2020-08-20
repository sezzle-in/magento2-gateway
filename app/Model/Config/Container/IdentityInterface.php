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
interface IdentityInterface
{
    /**
     * Check if payment method is enabled
     * @return bool
     */
    public function isEnabled();

    /**
     * Set store
     * @return Store
     */
    public function getStore();

    /**
     * Get Store
     * @param Store $store
     * @return mixed
     */
    public function setStore(Store $store);
}

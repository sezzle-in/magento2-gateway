<?php

use Magento\Framework\App\Bootstrap;

require 'app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode('frontend');

$options = getopt("", array(
    "email::",
    "password::",
));

$customerData = array_merge(array(
    first_name => 'Sezzle',
    last_name => 'Customer',
    email => 'szl.user@getnada.com',
    password => '1password'
), $options);

$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
$storeId = $storeManager->getStore()->getId();
$websiteId = $storeManager->getStore($storeId)->getWebsiteId();

try {
    $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
    if ($customerFactory->loadByEmail($customerData['email'])->getId()) {
        $errMesg = 'There is already an account with this email address ' . $customerData['email'];
    }
    $customer->setWebsiteId($websiteId);
    $customer->setEmail($customerData['email']);
    $customer->setFirstname($customerData['first_name']);
    $customer->setLastname($customerData['last_name']);
    $hashedPassword = $objectManager->get('\Magento\Framework\Encryption\EncryptorInterface')->getHash($customerData['password'], true);

    $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface')->save($customer, $hashedPassword);

    $customer = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
    $customer->setWebsiteId($websiteId)->loadByEmail($customerData['email']);
} catch (Exception $e) {
    echo $e->getMessage();
}
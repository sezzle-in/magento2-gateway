<?php
/*
 * @category    Sezzle
 * @package     Sezzle_Sezzlepay
 * @copyright   Copyright (c) Sezzle (https://www.sezzle.in/)
 */

namespace Sezzle\Sezzlepay\Model\Api;

use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\ZendClient;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Psr\Log\LoggerInterface as Logger;
use Sezzle\Sezzlepay\Helper\Data as SezzleHelper;

/**
 * Class Processor
 * @package Sezzle\Sezzlepay\Model\Api
 */
class Processor implements ProcessorInterface
{

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var ScopeConfig
     */
    protected $scopeConfig;
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var SezzleHelper
     */
    protected $sezzleHelper;

    /**
     * Processor constructor.
     * @param Curl $curl
     * @param SezzleHelper $sezzleHelper
     * @param JsonHelper $jsonHelper
     * @param Logger $logger
     * @param ScopeConfig $scopeConfig
     */
    public function __construct(
        Curl $curl,
        SezzleHelper $sezzleHelper,
        JsonHelper $jsonHelper,
        Logger $logger,
        ScopeConfig $scopeConfig
    ) {
        $this->curl = $curl;
        $this->sezzleHelper = $sezzleHelper;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Call to Sezzle Gateway
     *
     * @param $url
     * @param $authToken
     * @param bool $body
     * @param string $method
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function call($url, $authToken = null, $body = false, $method = ZendClient::GET)
    {
        try {
            if ($authToken) {
                $this->curl->addHeader("Authorization", "Bearer $authToken");
            }
            $this->sezzleHelper->logSezzleActions("Auth token : $authToken");
            $this->sezzleHelper->logSezzleActions("****Request Info****");
            $requestLog = [
                'type' => 'Request',
                'method' => $method,
                'url' => $url,
                'body' => $body
            ];
            $this->sezzleHelper->logSezzleActions($requestLog);
            $this->curl->setTimeout(ApiParamsInterface::TIMEOUT);
            $this->curl->addHeader("Content-Type", ApiParamsInterface::CONTENT_TYPE_JSON);
            switch ($method) {
                case 'POST':
                    $this->curl->post($url, $this->jsonHelper->jsonEncode($body));
                    break;
                case 'GET':
                    $this->curl->get($url);
                    break;
                default:
                    break;
            }

            $response = $this->curl->getBody();

            $responseLog = [
                'type' => 'Response',
                'method' => $method,
                'url' => $url,
                'httpStatusCode' => $this->curl->getStatus()
            ];
            $this->sezzleHelper->logSezzleActions("****Response Info****");
            $this->sezzleHelper->logSezzleActions($responseLog);
        } catch (\Exception $e) {
            $this->sezzleHelper->logSezzleActions($e->getMessage());
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Gateway error: %1', $e->getMessage())
            );
        }
        return $response;
    }
}

<?php

namespace Rackspace\Cloudfiles\Authentication;

use Rackspace\Cloudfiles\CoreConfig;

class Config
{
    protected $coreConfig;
    protected $storageUrl;
    protected $authToken;
    protected $username;
    protected $account;
    protected $cdnmUrl;
    protected $apiKey;

    /**
     * Setup the Authentication configuration object
     *
     * @param Rackspace\Cloudfiles\Config $config
     * @param array $config
     */
    public function __construct(CoreConfig $coreConfig, array $config = array())
    {
        $username = isset($config['username']) ? $config['username'] : null;
        $apiKey   = isset($config['apiKey']) ? $config['apiKey'] : null;
        $account  = isset($config['account']) ? $config['account'] : null;

        $this
            ->setCoreConfig($coreConfig)
            ->setUsername($username)
            ->setApiKey($apiKey)
            ->setAccount($account);
    }

    public function setCoreConfig(CoreConfig $coreConfig)
    {
        $this->coreConfig = $coreConfig;
        return $this;
    }

    public function getCoreConfig()
    {
        return $this->coreConfig;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setStorageUrl($storageUrl)
    {
        $this->storageUrl = $storageUrl;
        return $this;
    }

    public function getStorageUrl()
    {
        return $this->storageUrl;
    }

    public function setCdnmUrl($cdnmUrl)
    {
        $this->cdnmUrl = $cdnmUrl;
        return $this;
    }

    public function getCdnmUrl()
    {
        return $this->cdnmUrl;
    }

    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function getAuthToken()
    {
        return $this->authToken;
    }

    public function __call($methodName, $arguments)
    {
        return call_user_func_array(array($this->coreConfig, $methodName), $arguments);
    }
}
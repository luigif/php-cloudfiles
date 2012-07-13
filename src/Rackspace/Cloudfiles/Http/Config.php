<?php

namespace Rackspace\Cloudfiles\Http;

use Rackspace\Cloudfiles\CoreConfig;

class Config
{
    protected $coreConfig;
    protected $storageUrl;
    protected $authToken;
    protected $cdnmUrl;

    /**
     * Setup the Http configuration object
     *
     * @param Rackspace\Cloudfiles\Config $config
     */
    public function __construct(CoreConfig $coreConfig)
    {
        $this->setCoreConfig($coreConfig);
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
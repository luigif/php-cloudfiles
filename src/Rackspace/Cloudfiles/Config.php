<?php

namespace Rackspace\Cloudfiles;

/**
 * Abstract base class for Rackspace Cloudfiles classes
 */
abstract class Config
{
    protected $defaultCloudFilesApiVersion;
    protected $maxContainerNameLength;
    protected $maxObjectNameLength;
    protected $maxObjectSize;
    protected $authUrls;
    protected $cloudFilesCABundlePath;
    protected $cloudFilesMagicPath;

    protected $defaultParameters = array(
        'defaultCloudFilesApiVersion' => 1,
        'maxContainerNameLength'      => 256,
        'maxObjectNameLength'         => 1024,
        'maxObjectSize'               => 5368709121, // 1 + 5 * 1024^3
        'authUrls'                    => array(
            'uk' => 'https://auth.api.rackspacecloud.com',
            'us' => 'https://lon.auth.api.rackspacecloud.com',
        ),
    );

    protected $mandatoryParameters = array(
        'defaultCloudFilesApiVersion',
        'maxContainerNameLength',
        'maxObjectNameLength',
        'maxObjectSize',
        'authUrls',
        'cloudFilesCABundlePath',
        'cloudFilesMagicPath'
    );

    public function setDefaultCloudFilesApiVersion($defaultCloudFilesApiVersion)
    {
        $this->$defaultCloudFilesApiVersion = $defaultCloudFilesApiVersion;
        return $this;
    }

    public function getDefaultCloudFilesApiVersion()
    {
        return $this->$defaultCloudFilesApiVersion;
    }

    public function setMaxContainerNameLength($maxContainerNameLength)
    {
        $this->maxContainerNameLength = $maxContainerNameLength;
        return $this;
    }

    public function getMaxContainerNameLength()
    {
        return $this->maxContainerNameLength;
    }

    public function setMaxObjectNameLength($maxObjectNameLength)
    {
        $this->maxObjectNameLength = $maxObjectNameLength;
        return $this;
    }

    public function getMaxObjectNameLength()
    {
        return $this->maxObjectNameLength;
    }

    public function setMaxObjectSize($maxObjectSize)
    {
        $this->maxObjectSize = $maxObjectSize;
        return $this;
    }

    public function getMaxObjectSize()
    {
        return $this->maxObjectSize;
    }

    public function setAuthUrls(array $authUrls)
    {
        $this->authUrls = $authUrls;
        return $this;
    }

    public function setAuthUrl($authUrl, $index)
    {
        $this->authUrl[$index] = $authUrl;
        return $this;
    }

    public function addAuthUrl($authUrl, $index)
    {
        if (isset($this->authUrl[$index])) {
            throw new \RuntimeException("Index $index already exists");
        }
        $this->authUrl[$index] = $authUrl;
        return $this;
    }

    public function removeAuthUrl($index)
    {
        if (isset($this->authUrl[$index])) {
            throw new \RuntimeException("Index $index doesn't exist");
        }
        unset($this->authUrl[$index]);
        return $this;
    }

    public function getAuthUrls()
    {
        return $this->authUrls;
    }

    public function getAuthUrl($index)
    {
        if (isset($this->authUrls[$index])) {
            return $this->authUrls[$index];
        }

        throw new \RuntimeException("Index $index doesn't exist");
    }

    public function setCloudFilesCABundlePath($cloudFilesCABundlePath)
    {
        $this->cloudFilesCABundlePath = $cloudFilesCABundlePath;
        return $this;
    }

    public function getCloudFilesCABundlePath()
    {
        return $this->cloudFilesCABundlePath;
    }

    public function setCloudFilesMagicPath($cloudFilesMagicPath)
    {
        $this->cloudFilesMagicPath = $cloudFilesMagicPath;
        return $this;
    }

    public function getCloudFilesMagicPath()
    {
        return $this->cloudFilesMagicPath;
    }
}
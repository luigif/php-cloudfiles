<?php

namespace Rackspace\Cloudfiles;

use Agavee\Toolbox\Parameters\Checker as ParametersChecker;

/**
 * Abstract common config class for Rackspace Cloudfiles classes
 */
class CoreConfig
{
    protected $defaultCloudFilesApiVersion;
    protected $maxContainerNameLength;
    protected $cloudFilesCABundlePath;
    protected $cloudFilesMagicPath;
    protected $defaultAuthUrlIndex;
    protected $maxObjectNameLength;
    protected $defaultAuthUrl;
    protected $maxObjectSize;
    protected $authUrls;

    protected $defaultParameters = array(
        'defaultCloudFilesApiVersion' => 1,
        'maxContainerNameLength'      => 256,
        'maxObjectNameLength'         => 1024,
        'defaultAuthUrlIndex'         => 'us',
        'maxObjectSize'               => 5368709121, // 1 + 5 * 1024^3
        'authUrls'                    => array(
            'us' => 'https://auth.api.rackspacecloud.com',
            'uk' => 'https://lon.auth.api.rackspacecloud.com',
        ),
        'debug'                       => false,
    );

    protected $mandatoryParameters = array(
        'defaultCloudFilesApiVersion',
        'maxContainerNameLength',
        'cloudFilesCABundlePath',
        'cloudFilesMagicPath',
        'maxObjectNameLength',
        'defaultAuthUrlIndex',
        'maxObjectSize',
        'authUrls',
    );

    /**
     * Setup the configuration object
     *
     * @param array $config
     *   the two mandatory parameters are:
     *     *  cloudFilesCABundlePath
     *     *  cloudFilesMagicPath
     *   the other ones have sensible default but can be overridden
     */
    public function __construct(array $config = array())
    {
        $config = array_merge($this->defaultParameters, $config);

        ParametersChecker::check($config, $this->mandatoryParameters);

        // Mandatory parameters
        $this
            ->setDefaultCloudFilesApiVersion($config['defaultCloudFilesApiVersion'])
            ->setMaxContainerNameLength($config['maxContainerNameLength'])
            ->setCloudFilesCABundlePath($config['cloudFilesCABundlePath'])
            ->setCloudFilesMagicPath($config['cloudFilesMagicPath'])
            ->setMaxObjectNameLength($config['maxObjectNameLength'])
            ->setDefaultAuthUrlIndex($config['defaultAuthUrlIndex'])
            ->setMaxObjectSize($config['maxObjectSize'])
            ->setAuthUrls($config['authUrls']);

        // Optional parameters
        $debug = isset($config['debug']) ? $config['debug'] : false;

        $this->setDebug($debug);
    }

    public function setDefaultCloudFilesApiVersion($defaultCloudFilesApiVersion)
    {
        $this->defaultCloudFilesApiVersion = $defaultCloudFilesApiVersion;
        return $this;
    }

    public function getDefaultCloudFilesApiVersion()
    {
        return $this->defaultCloudFilesApiVersion;
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

    public function setDefaultAuthUrlIndex($defaultAuthUrlIndex)
    {
        $this->defaultAuthUrlIndex = $defaultAuthUrlIndex;
        return $this;
    }

    public function getDefaultAuthUrlIndex()
    {
        return $this->defaultAuthUrlIndex;
    }

    public function getDefaultAuthUrl()
    {
        return $this->authUrls[$this->defaultAuthUrlIndex];
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

    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    public function getDebug()
    {
        return $this->debug;
    }
}
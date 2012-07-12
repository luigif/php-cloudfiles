<?php

namespace Rackspace\Cloudfiles;

use Agavee\Toolbox\Parameters\Checker as ParametersChecker;

class ArrayConfig extends Config
{
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

        ParametersChecker::check($this->mandatoryParameters, $config);

        $this
            ->setDefaultCloudFilesApiVersion($config['defaultCloudFilesApiVersion'])
            ->setMaxContainerNameLength($config['maxContainerNameLength'])
            ->setMaxObjectNameLength($config['maxObjectNameLength'])
            ->setMaxObjectSize($config['maxObjectSize'])
            ->setAuthUrls($config['authUrls'])
            ->setCloudFilesCABundlePath($config['cloudFilesCABundlePath'])
            ->setCloudFilesMagicPath($config['cloudFilesMagicPath']);
    }
}
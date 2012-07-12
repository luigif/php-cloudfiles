<?php

namespace Rackspace\Cloudfiles\Config;

use Agavee\Toolbox\Parameters\Checker as ParametersChecker;

class IniConfig extends Config
{
    /**
     * Setup the configuration object
     *
     * @param string $path
     *   path of the ini config file on the filesystem
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("File $path does not exist");
        }

        $config = array_merge($this->defaultParameters, parse_ini_file($path, false));

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
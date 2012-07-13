<?php

namespace Rackspace\Cloudfiles;

interface Configurable {
    public function setConfiguration($configuration);
    public function getConfiguration();
}
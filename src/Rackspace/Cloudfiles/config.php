<?php

define("DEFAULT_CF_API_VERSION", 1);
define("MAX_CONTAINER_NAME_LEN", 256);
define("MAX_OBJECT_NAME_LEN", 1024);
define("MAX_OBJECT_SIZE", 5*1024*1024*1024+1);
define("US_AUTHURL", "https://auth.api.rackspacecloud.com");
define("UK_AUTHURL", "https://lon.auth.api.rackspacecloud.com");

define("CF_CABUNDLE_PATH", dirname(__FILE__) . "/../../../share/cacert.pem");
define("CF_MAGIC_PATH", dirname(__FILE__) . "/../../../share/magic");
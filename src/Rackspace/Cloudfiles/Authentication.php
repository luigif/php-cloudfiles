<?php

namespace Rackspace\Cloudfiles;

use Rackspace\Cloudfiles\Authentication\Config as AuthenticationConfig;
use Rackspace\Cloudfiles\Http\Config as HttpConfig;
use Rackspace\Cloudfiles\Http;

use Rackspace\Exception\AuthenticationException;
use Rackspace\Exception\InvalidResponseException;
use Rackspace\Exception\SyntaxException;

/**
 * Class for handling Cloud Files Authentication, call it's {@link authenticate()}
 * method to obtain authorized service urls and an authentication token.
 *
 * Example:
 * <code>
 * # Create the authentication instance
 * #
 * $auth = new Authentication("username", "api_key");
 *
 * # NOTE: For UK Customers please specify your AuthURL Manually
 * # There is a Predfined constant to use EX:
 * #
 * # $auth = new Authentication("username, "api_key", null, UK_AUTHURL);
 * # Using the UK_AUTHURL keyword will force the api to use the UK AuthUrl.
 * # rather then the US one. The null Is passed for legacy purposes and must
 * # be passed to function correctly.
 *
 * # NOTE: Some versions of cURL include an outdated certificate authority (CA)
 * #       file.  This API ships with a newer version obtained directly from
 * #       cURL's web site (http://curl.haxx.se).  To use the newer CA bundle,
 * #       call the Authentication instance's 'ssl_use_cabundle()' method.
 * #
 * # $auth->ssl_use_cabundle(); # bypass cURL's old CA bundle
 *
 * # Perform authentication request
 * #
 * $auth->authenticate();
 * </code>
 *
 * @package php-cloudfiles
 */
class Authentication implements Configurable
{
    private $config;

    /**
     * Class constructor (PHP 5 syntax)
     *
     * @param Rackspace\Cloudfiles\Authentication\Config $config
     */
    public function __construct(AuthenticationConfig $config)
    {
        $this->config     = $config;

        $this->cfs_http = new Http(new HttpConfig($this->config->getCoreConfig()));
    }

    public function setConfiguration($configuration)
    {
        $this->config = $configuration;
        return $this;
    }

    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Use the Certificate Authority bundle included with this API
     *
     * Most versions of PHP with cURL support include an outdated Certificate
     * Authority (CA) bundle (the file that lists all valid certificate
     * signing authorities).  The SSL certificates used by the Cloud Files
     * storage system are perfectly valid but have been created/signed by
     * a CA not listed in these outdated cURL distributions.
     *
     * As a work-around, we've included an updated CA bundle obtained
     * directly from cURL's web site (http://curl.haxx.se).  You can direct
     * the API to use this CA bundle by calling this method prior to making
     * any remote calls.  The best place to use this method is right after
     * the Authentication instance has been instantiated.
     *
     * You can specify your own CA bundle by passing in the full pathname
     * to the bundle.  You can use the included CA bundle by leaving the
     * argument blank.
     *
     * @param string $path Specify path to CA bundle (default to included)
     */
    function ssl_use_cabundle($path = null)
    {
        $this->cfs_http->ssl_use_cabundle($path);
    }

    /**
     * Attempt to validate Username/API Access Key
     *
     * Attempts to validate credentials with the authentication service.  It
     * either returns <kbd>true</kbd> or throws an Exception.  Accepts a single
     * (optional) argument for the storage system API version.
     *
     * Example:
     * <code>
     * # Create the authentication instance
     * #
     * $auth = new Authentication("username", "api_key");
     *
     * # Perform authentication request
     * #
     * $auth->authenticate();
     * </code>
     *
     * @param string $version API version for Auth service (optional)
     * @return boolean <kbd>true</kbd> if successfully authenticated
     * @throws AuthenticationException invalid credentials
     * @throws InvalidResponseException invalid response
     */
    function authenticate($version = null)
    {
        if (null === $version) {
            $version = $this->config->getDefaultCloudFilesApiVersion();
        }

        list($status, $reason, $storage_url, $curl, $atoken) =
                $this->cfs_http->authenticate($this->config->getUsername(), $this->config->getApiKey(),
                $this->config->getAccount(), $this->config->getDefaultAuthUrl());

        if ($status == 401) {
            throw new AuthenticationException("Invalid username or access key.");
        }

        if ($status < 200 || $status > 299) {
            throw new InvalidResponseException("Unexpected response ($status): $reason");
        }

        if (!($storage_url || $curl) || !$atoken) {
            throw new InvalidResponseException("Expected headers missing from auth service.");
        }

        $this->config->setStorageUrl($storage_url);
        $this->config->setCdnmUrl($curl);
        $this->config->setAuthToken($atoken);

        return true;
    }
        /**
         * Use Cached Token and Storage URL's rather then grabbing from the Auth System
         *
         * Example:
         * <code>
         * #Create an Auth instance
         * $auth = new Authentication();
         * #Pass Cached URL's and Token as Args
         * $auth->load_cached_credentials("auth_token", "storage_url", "cdn_management_url");
         * </code>
         *
         * @param string $auth_token A Cloud Files Auth Token (Required)
         * @param string $storage_url The Cloud Files Storage URL (Required)
         * @param string $cdnm_url CDN Management URL (Required)
         * @return boolean <kbd>true</kbd> if successful
         * @throws SyntaxException If any of the Required Arguments are missing
         */
    function load_cached_credentials($auth_token, $storage_url, $cdnm_url)
    {
        if (!$storage_url || !$cdnm_url) {
            throw new SyntaxException("Missing Required Interface URL's!");
            return false;
        }

        if (!$auth_token) {
            throw new SyntaxException("Missing Auth Token!");
            return false;
        }

        $this->config
            ->setStorageUrl($storage_url)
            ->setCdnmUrl($cdnm_url)
            ->setAuthToken($auth_token);

        return true;
    }

    /**
         * Grab Cloud Files info to be Cached for later use with the load_cached_credentials method.
         *
     * Example:
         * <code>
         * #Create an Auth instance
         * $auth = new Authentication("UserName","API_Key");
         * $auth->authenticate();
         * $array = $auth->export_credentials();
         * </code>
         *
     * @return array of url's and an auth token.
         */
    function export_credentials()
    {
        return array(
            'storage_url' => $this->config->getStorageUrl(),
            'cdnm_url'    => $this->config->getCdnmUrl(),
            'auth_token'  => $this->config->getAuthToken(),
        );
    }


    /**
     * Make sure the Authentication instance has authenticated.
     *
     * Ensures that the instance variables necessary to communicate with
     * Cloud Files have been set from a previous authenticate() call.
     *
     * @return boolean <kbd>true</kbd> if successfully authenticated
     */
    function authenticated()
    {
        if (!($this->config->getStorageUrl() || $this->config->getCdnmUrl()) || !$this->config->getAuthToken()) {
            return false;
        }

        return true;
    }
}
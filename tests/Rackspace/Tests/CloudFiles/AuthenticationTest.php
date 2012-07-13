<?php # -*- compile-command: (concat "phpunit " buffer-file-name) -*-

namespace Rackspace\Tests\Cloudfiles;

use Rackspace\Cloudfiles\Authentication;
use Rackspace\Cloudfiles\Connection;
use Rackspace\Cloudfiles\CoreConfig;
use Rackspace\Cloudfiles\Authentication\Config as AuthenticationConfig;

require_once 'common.php';

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    private $authenticationConfig;
    private $fixturesDir;
    private $coreConfig;

    public function __construct() {
        $this->authenticationConfig = null;
        $this->fixturesDir          = realpath(__DIR__ . '/../Fixtures');
        $this->coreConfig           = null;
    }

    protected function setUp() {
        $this->coreConfig = new CoreConfig(array(
            'cloudFilesCABundlePath' => $this->fixturesDir . '/cacert.pem',
            'cloudFilesMagicPath'    => $this->fixturesDir . '/magic',
        ));

        $this->authenticationConfig = new AuthenticationConfig($this->coreConfig, array(
            'username' => USER,
            'apiKey'   => API_KEY,
            'account'  => ACCOUNT,
        ));
    }

    public function testTokenCache()
    {
        $auth =  new Authentication($this->authenticationConfig);
        $auth->authenticate();

        $arr = $auth->export_credentials();
        $this->assertNotNull($arr['storage_url']);
        $this->assertNotNull($arr['cdnm_url']);
        $this->assertNotNull($arr['auth_token']);
    }

    public function testTokenAuth()
    {
        $auth =  new Authentication($this->authenticationConfig);
        $auth->authenticate();

        $arr = $auth->export_credentials();
        $this->assertNotNull($arr['storage_url']);
        $this->assertNotNull($arr['cdnm_url']);
        $this->assertNotNull($arr['auth_token']);

        $auth = new Authentication($this->authenticationConfig);
        $auth->load_cached_credentials($arr['auth_token'], $arr['storage_url'], $arr['cdnm_url']);
        $conn = new Connection($this->coreConfig, $auth);
    }

    public function testTokenErrors()
    {
        $auth =  new Authentication($this->authenticationConfig);
        $auth->authenticate();

        $arr = $auth->export_credentials();
        $this->assertNotNull($arr['storage_url']);
        $this->assertNotNull($arr['cdnm_url']);
        $this->assertNotNull($arr['auth_token']);

        $auth = new Authentication($this->authenticationConfig);

        $this->setExpectedException('Rackspace\\Exception\\SyntaxException');
        $auth->load_cached_credentials(NULL, $arr['storage_url'], $arr['cdnm_url']);
        $this->setExpectedException('Rackspace\\Exception\\SyntaxException');
        $auth->load_cached_credentials($arr['auth_token'], NULL, $arr['cdnm_url']);
        $this->setExpectedException('Rackspace\\Exception\\SyntaxException');
        $auth->load_cached_credentials($arr['auth_token'], $arr['storage_url'], NULL);
    }

    public function testBadAuthentication()
    {
        $this->setExpectedException('Rackspace\\Exception\\AuthenticationException');

        $authenticationConfig = new AuthenticationConfig($this->coreConfig, array(
            'username' => 'foo',
            'apiKey'   => 'bar',
        ));

        $auth =  new Authentication($authenticationConfig);
        $auth->authenticate();
    }

    public function testAuthenticationAttributes()
    {
        $auth =  new Authentication($this->authenticationConfig);
        $auth->authenticate();

        $this->assertNotNull($auth->getConfiguration()->getStorageUrl());
        $this->assertNotNull($auth->getConfiguration()->getAuthToken());

        if (ACCOUNT) {
            $this->assertNotNull($auth->cdnm_url);
        }
    }
    public function testUkAuth()
    {
        $this->assertTrue($this->coreConfig->getAuthUrl('uk') === 'https://lon.auth.api.rackspacecloud.com');
    }
}
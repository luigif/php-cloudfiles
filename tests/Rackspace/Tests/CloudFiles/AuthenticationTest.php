<?php # -*- compile-command: (concat "phpunit " buffer-file-name) -*-

namespace Rackspace\Tests\Cloudfiles;

use Rackspace\Cloudfiles\Authentication;
use Rackspace\Cloudfiles\Connection;

require_once 'common.php';

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    function __construct() {
        $this->auth = null;
    }

    protected function setUp() {
        $this->auth = new Authentication(USER, API_KEY);
        $this->auth->authenticate();
        $conn = new Connection($this->auth);
    }

    public function testTokenCache()
    {
        $this->auth =  new Authentication(USER, API_KEY);
        $this->auth->authenticate();
        $arr = $this->auth->export_credentials();
        $this->assertNotNull($arr['storage_url']);
        $this->assertNotNull($arr['cdnm_url']);
        $this->assertNotNull($arr['auth_token']);
    }

    public function testTokenAuth()
    {
        $this->auth =  new Authentication(USER, API_KEY);
        $this->auth->authenticate();
        $arr = $this->auth->export_credentials();
        $this->assertNotNull($arr['storage_url']);
        $this->assertNotNull($arr['cdnm_url']);
        $this->assertNotNull($arr['auth_token']);
        $this->auth = new Authentication();
        $this->auth->load_cached_credentials($arr['auth_token'], $arr['storage_url'], $arr['cdnm_url']);
        $conn = new Connection($this->auth);
    }

    public function testTokenErrors()
    {
        $auth =  new Authentication(USER, API_KEY);
        $auth->authenticate();
        $arr = $auth->export_credentials();
        $this->assertNotNull($arr['storage_url']);
        $this->assertNotNull($arr['cdnm_url']);
        $this->assertNotNull($arr['auth_token']);
        $this->auth = new Authentication();
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
        $auth = new Authentication('e046e8db7d813050b14ce335f2511e83', 'bleurrhrhahra');
        $auth->authenticate();
    }

    public function testAuthenticationAttributes()
    {
        $this->assertNotNull($this->auth->storage_url);
        $this->assertNotNull($this->auth->auth_token);

        if (ACCOUNT) {
            $this->assertNotNull($this->auth->cdnm_url);
        }
    }
    public function testUkAuth()
    {
        $this->assertTrue(UK_AUTHURL === 'https://lon.auth.api.rackspacecloud.com');
    }
}

?>

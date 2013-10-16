<?php
namespace Aliegon\Tests\Auth;

use \Aliegon\Config;
use \Aliegon\Tests\Auth\User;
use \Aliegon\Auth\Auth;
class AuthTest extends \PHPUnit_Framework_TestCase
{
    private function getAuth($user = null)
    {
        $array = array('default' => array());
        $config = new Config($array);
        $session = $this->getSession($user);
        $auth = new Auth($config, $session);

        return $auth;
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testAuthLoginPassingAnObjectDifferentThanUserInterfaceShouldThrowAnError()
    {
        $user = null;
        $auth = $this->getAuth($user);
        $auth->login($user);
    }

    public function testAuthLoginHappyPath()
    {
        $user = new User();
        $auth = $this->getAuth($user);
        $auth->login($user);
    }

    public function testAuthGetUserShouldReturnFalseWhenUserIsNotLoggedIn()
    {
        $user = null;
        $auth = $this->getAuth($user);
        $userTest = $auth->getUser();

        $this->assertFalse($userTest);
    }

    public function testAuthGetUserHappyPath()
    {
        $user = new User;
        $auth = $this->getAuth($user);
        $auth->login($user);
        $userTest = $auth->getUser();
        
        $this->assertInstanceOf('\Aliegon\Auth\UserInterface', $userTest);
    }

    public function testAuthIsLoggedShouldReturnFalseIfAUserIsNotLoggedIn()
    {
        $user = null;
        $auth = $this->getAuth($user);
        $userTest = $auth->isLogged();
        
        $this->assertFalse($userTest);
    }

    public function testAuthIsLoggedHappyPath()
    {
        $user = new User;
        $auth = $this->getAuth($user);
        $auth->login($user);
        $userTest = $auth->isLogged();
        
        $this->assertTrue($userTest);
    }

    public function testAuthHasAccessToHappyPath()
    {
        $user = new User(array('homepage'));
        $auth = $this->getAuth($user);
        $auth->login($user);
        $userTest = $auth->hasAccessTo('homepage');
        
        $this->assertTrue($userTest);
    }

    public function testAuthHasAccessToShouldReturnFalseIfNoUserIsLogged()
    {
        $user = null;
        $auth = $this->getAuth($user);
        $userTest = $auth->hasAccessTo('homepage');
        
        $this->assertFalse($userTest);
    }

    public function testAuthHasAccessToShouldReturnFalseIfTheLoggedUserHasNoThatPermission()
    {
        $user = new User(array('profile'));
        $auth = $this->getAuth($user);
        $auth->login($user);
        $userTest = $auth->hasAccessTo('homapage');
        
        $this->assertFalse($userTest);
    }

    public function testUserManagerSignupHappyPath()
    {
        $return = null;
        $this->assertNull($return);
    }

    private function getSession($user = false)
    {
        $session = $this->getMock('\Aliegon\Session\Session', array('set', 'destroy', 'get'), array(), '', false,false);
        
        $session->expects($this->any())
                 ->method('set')
                 ->will($this->returnValue(true));

        $session->expects($this->any())
                 ->method('destroy')
                 ->will($this->returnValue(true));

        $session->expects($this->any())
                 ->method('get')
                 ->will($this->returnValue(serialize($user)));

        return $session;
    }
}
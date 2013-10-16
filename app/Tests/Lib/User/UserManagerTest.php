<?php
namespace app\Tests\Lib\User;

use app\Lib\User\UserManager;
use app\Entity\User;
class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException app\Lib\User\Exception\MissingFieldsUserManagerException
     */
    public function testUserManagerSignupWithoutFieldsThrowMissingFieldsUserManagerException()
    {
        $um = $this->getUserManager();
        $um->signup(array());
    }

    /**
     * @expectedException app\Lib\User\Exception\EmailNotValidUserManagerException
     */
    public function testUserManagerSignupWithWrongEmailThrowEmailNotValidUserManagerException()
    {
        $um = $this->getUserManager();
        $um->signup($this->getData(array('email' => 'dij')));
    }

    /**
     * @expectedException app\Lib\User\Exception\EmailNotUniqueUserManagerException
     */
    public function testUserManagerSignupWithNotUniqueEmailThrowEmailNotUniqueUserManagerException()
    {
        $um = $this->getUserManager(true);
        $um->signup($this->getData(array('email' => 'notUniqueEmail@email.com')));
    }

    /**
     * @expectedException app\Lib\User\Exception\PasswordDontMatchUserManagerException
     */
    public function testUserManagerSignupWithDifferentPasswordsThrowPasswordDontMatchUserManagerException()
    {
        $um = $this->getUserManager();
        $um->signup($this->getData(array('repeat_password' => 'differntPassword')));
    }

    /**
     * @expectedException app\Lib\User\Exception\PasswordNotValidUserManagerException
     */
    public function testUserManagerSignupWithANotValidPasswordThrowPasswordNotValidUserManagerException()
    {
        $um = $this->getUserManager();
        $um->signup($this->getData(array('password' => 'short', 'repeat_password' => 'short')));
    }

    public function testUserManagerSignupHappyPath()
    {
        $um = $this->getUserManager();

        $return = $um->signup($this->getData());
        $this->assertNull($return);
    }

    private function getData($data = array())
    {
        $default = array(
            'name' => 'test',
            'surname' => 'surname',
            'email' => 'email@test.com',
            'password' => 'password',
            'repeat_password' => 'password'
        );

        return array_merge($default, $data);
    }

    private function getUserManager($uniqueUser = false)
    {
        $em = $this->getEm($uniqueUser);

        return new UserManager($em);
    }

    private function getEm($uniqueUser = false)
    {
        $em = $this->getMock('em', array('getRepository', 'persist', 'flush'), array(), '', false,false);
        $repository = $this->getMock('repository', array('findOneBy'), array(), '', false,false);

        if($uniqueUser)
            $user = new User();
        else
            $user = null;

        $repository->expects($this->any())
                 ->method('findOneBy')
                 ->will($this->returnValue($user));

        $em->expects($this->any())
                 ->method('getRepository')
                 ->will($this->returnValue($repository));

        return $em;
    }
}
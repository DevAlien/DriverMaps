<?php
namespace app\Tests\Lib\Event;

use app\Lib\Event\EventManager;
use app\Entity\Event;
class EventManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException app\Lib\Event\Exception\MissingFieldsEventManagerException
     */
    public function testEventManagerValidateWithoutFieldsThrowMissingFieldsEventManagerException()
    {
        $um = $this->getEventManager();
        $um->validate(array());
    }
    
    public function testEventManagerValidateWithAllFieldsReturnsTrue()
    {

        $um = $this->getEventManager();
        $return = $um->validate(array(
                'title' => 'test',
                'description' => 'test',
                'picture' => 'test',
                'max_people' => 10,
                'price' => 5,
                'time_start' => '10:00'
            ));

        $this->assertTrue($return);
    }


    public function testEventManagerTest()
    {

    }
    private function getEventManager($uniqueUser = false)
    {
        $em = $this->getEm($uniqueUser);

        return new EventManager($em);
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
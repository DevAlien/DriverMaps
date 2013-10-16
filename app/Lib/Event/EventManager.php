<?php
namespace app\Lib\Event;

use Aliegon\Controller\Controller;
use app\Entity\User;
use app\Entity\UserData;
use app\Lib\Event\Exception\MissingFieldsEventManagerException;
use app\Lib\User\Exception\PasswordDontMatchUserManagerException;
use app\Lib\User\Exception\PasswordNotValidUserManagerException;
use app\Lib\User\Exception\EmailNotValidUserManagerException;
use app\Lib\User\Exception\EmailNotUniqueUserManagerException;
use app\Lib\User\Exception\UserNotFoundUserManagerException;

class EventManager
{
    private $em;

    public function __construct($em = false)
    {
        $this->em = $em;
    }

    public function validate(array $data)
    {
    	if($this->validateFieldsExist($data) !== true)
    		throw new MissingFieldsEventManagerException();

    	return true;
    }

    private function validateFieldsExist(array $data)
    {
        $needed = array(
                'title',
                'description',
                'picture',
                'max_people',
                'price',
                'time_start'
            );
        
        foreach($needed as $field) {
            if(!array_key_exists($field, $data))
                return $field;
        }

        return true;
    }
}
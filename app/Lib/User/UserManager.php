<?php
namespace app\Lib\User;

use Aliegon\Controller\Controller;
use app\Entity\User;
use app\Entity\UserData;
use app\Lib\User\Exception\MissingFieldsUserManagerException;
use app\Lib\User\Exception\PasswordDontMatchUserManagerException;
use app\Lib\User\Exception\PasswordNotValidUserManagerException;
use app\Lib\User\Exception\EmailNotValidUserManagerException;
use app\Lib\User\Exception\EmailNotUniqueUserManagerException;
use app\Lib\User\Exception\UserNotFoundUserManagerException;

class UserManager
{
    private $em;

    public function __construct($em = false)
    {
        $this->em = $em;
    }

    public function signup(array $data)
    {
        $exist = $this->validateFieldsExist($data);
        if($exist !== true)
            throw new MissingFieldsUserManagerException('field "' . $exist. '"');

        if(!$this->isEmailValid($data['email']))
            throw new EmailNotValidUserManagerException('Email is not valid');

        if(!$this->isEmailUnique($data['email']))
            throw new EmailNotUniqueUserManagerException('Email already exists');

        if(!$this->passwordMatches($data))
            throw new PasswordDontMatchUserManagerException('Passwords do not match');

        if(!$this->isPasswordValid($data['password']))
            throw new PasswordNotValidUserManagerException('Password is not valid');

        $this->saveNewUser($data);
    }

    public function validateForLogin(array $data)
    {
        $exist = $this->validateFieldsLoginExist($data);
        if($exist !== true)
            throw new MissingFieldsUserManagerException('field "' . $exist. '"');

        $user = $this->getUser($data['email']);
        if(!$user)
            throw new UserNotFoundUserManagerException('User not found');

        if($user->getPassword() !== md5($user->getSalt() . $data['password'] . $user->getSalt()))
            throw new PasswordDontMatchUserManagerException('Passwords do not match the email');

        return $user;
    }

    private function generateSalt($qtd = 20)
    { 
        //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code. 
        $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; 
        $QuantidadeCaracteres = strlen($Caracteres); 
        $QuantidadeCaracteres--; 

        $Hash=NULL; 
        for($x=1;$x<=$qtd;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $Hash .= substr($Caracteres,$Posicao,1); 
        } 

        return $Hash; 
    } 
    private function saveNewUser($data)
    {
        $user = new User();
        $user->setEmail($data['email']);
        $user->setSalt($this->generateSalt());
        $user->setPassword($data['password']);

        $userData = new UserData();
        $userData->setName($data['name']);
        $user->setUserData($userData);

        $this->em->persist($user);
        $this->em->flush();
    }

    private function getUser($email)
    {
        $em = $this->getEm();
        $userRepo = $em->getRepository('DriverMaps:User');
        $user = $userRepo->findOneBy(array('email' => $email));

        return $user;
    }

    private function isEmailUnique($email)
    {
        $user = $this->getUser($email);
        
        if($user)
            return false;

        return true;
    }

    private function isEmailValid($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    private function isPasswordValid($data)
    {
        if(strlen($data) > 6)
            return true;

        return false;
    }

    private function passwordMatches($data)
    {
        if($data['password'] == $data['repeat_password'])
            return true;

        return false;
    }

    private function validateFieldsExist(array $data)
    {
        $needed = array(
                'name',
                'email',
                'password',
                'repeat_password'
            );
        
        foreach($needed as $field) {
            if(!array_key_exists($field, $data))
                return $field;
        }

        return true;
    }

    private function validateFieldsLoginExist(array $data)
    {
        $needed = array(
                'email',
                'password',
            );
        
        foreach($needed as $field) {
            if(!array_key_exists($field, $data))
                return $field;
        }

        return true;
    }

    private function getEm()
    {
        return $this->em;
    }

    private function setEm($em)
    {
        $this->em = $em;

        return $this;
    }
}
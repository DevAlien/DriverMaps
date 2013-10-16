<?php
namespace Aliegon\Auth;

class Auth
{

    const COOKIE_NAME = 'identity';

    private $config;

    private $session;

    public function __construct(\Aliegon\Config $config, \Aliegon\Session\Session $session)
    {
        $this->config = $config;
        $this->session = $session;
    }
    
    public function hasAccessTo($smth) 
    {
        $user = $this->getUser();
        if ($user) {
            if(in_array($smth, $user->getPermissions()))
                return true;
        }

        return false;
    }

    public function login(\Aliegon\Auth\UserInterface $user) {
        $this->session->set('user', serialize($user));
    }

    public function logout() {
        $this->session->destroy('user');
    }

    public function getUser() {
        $user = unserialize($this->session->get('user'));
        if($user instanceof \Aliegon\Auth\UserInterface)
            return $user;

        return false;
    }

    public function isLogged()
    {
        $user = unserialize($this->session->get('user'));
        if($user instanceof \Aliegon\Auth\UserInterface)
            return true;

        return false;
    }

    public function crypt($text, $key = 'qMM1bZw990qwu4ZjjB3b4510e03B0eu5') {
        return base64_encode(
                        mcrypt_encrypt(
                                MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(
                                        mcrypt_get_iv_size(
                                                MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                                        ), MCRYPT_RAND
                                )
                        )
        );
    }

    public function decrypt($text, $key = 'qMM1bZw990qwu4ZjjB3b4510e03B0eu5') {
        return mcrypt_decrypt(
                        MCRYPT_RIJNDAEL_256, $key, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(
                                mcrypt_get_iv_size(
                                        MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                                ), MCRYPT_RAND
                        )
        );
    }

}

?>
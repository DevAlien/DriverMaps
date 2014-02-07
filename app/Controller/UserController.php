<?php
namespace app\Controller;

use Aliegon\Controller\Controller;
use app\Entity\User;
use app\Lib\User\UserManager;

class UserController extends Controller
{

    public function profileAction()
    {
        $em = $this->get('em');
        $userRepo = $em->getRepository('Cookmehome:User');
        $user = $userRepo->findOneById($this->params['userId']);
        if(!$user)
            throw new \Exception('NO USER FOUND');
        
        $auth = $this->get('auth');
        $authUser = $auth->getUser();
        $this->getTpl()->assign('own_profile', (($authUser && ($this->params['userId'] == $authUser->getId())) ? true : false));
        $this->getTpl()->assign('profile', $user);

        echo $this->getTpl()->burn('profile', 'html');
    }

    public function signupAction()
    {
        $em = $this->get('em');
        $req = $this->get('request');

        $userManager = new UserManager($em);
        
        try{
            $userManager->signup($req->getPost());
            echo json_encode(array('response' => true));
        } catch(\Exception $e) {
            echo json_encode(array('response' => false, 'message' => $e->getMessage()));
        }
    }

    public function loginAction()
    {
        $em = $this->get('em');
        $req = $this->get('request');
        
        $userManager = new UserManager($em);
        
        try{
            $user = $userManager->validateForLogin($req->getPost());
            $user->getUserData()->getName();
            $auth = $this->get('auth');
            $auth->login($user);
            echo json_encode(array('response' => true));
        } catch(\Exception $e) {
            echo json_encode(array('response' => false, 'message' => $e->getMessage()));
        }
    }

    public function logoutAction()
    {
        $auth = $this->get('auth');
        $auth->logout();
        $this->get('router')->redirect('homepage');
    }

    public function uploadProfilePictureAction()
    {

        $auth = $this->get('auth');
        $user = $auth->getUser();
        if (!$user) {
            echo json_encode(array('error' => 'User not logged in'));
            return;
        }

        $dir = '/' . $this->get('config')->get('media_directory') . '/' . $user->getId();
        if (!is_dir(ROOT . '/' . $dir))
            mkdir(ROOT . '/' . $dir, 0777, true);

        $file = $dir . '/picture_' . time() . '.' . pathinfo($_FILES["profile-picture"]["name"], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES["profile-picture"]["tmp_name"], ROOT . $file);

        $userData = $user->getUserData();
        $userData->setProfilePicture($file);

        $em = $this->get('em');
        $em->merge($userData);
        $em->flush();

        echo json_encode(array('filename' => $file));
    }

    public function uploadProfileCoverAction()
    {

        $auth = $this->get('auth');
        $user = $auth->getUser();
        if (!$user) {
            echo json_encode(array('error' => 'User not logged in'));
            return;
        }

        $dir = '/' . $this->get('config')->get('media_directory') . '/' . $user->getId();
        if (!is_dir(ROOT . '/' . $dir))
            mkdir(ROOT . '/' . $dir, 0777, true);

        $file = $dir . '/cover_' . time() . '.' . pathinfo($_FILES["profile-cover"]["name"], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES["profile-cover"]["tmp_name"], ROOT . $file);

        $userData = $user->getUserData();
        $userData->setTopPicture($file);

        $em = $this->get('em');
        $em->merge($userData);
        $em->flush();

        echo json_encode(array('filename' => $file));
    }
}
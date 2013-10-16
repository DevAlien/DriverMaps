<?php
namespace app\Controller;

use Aliegon\Controller\Controller;
use app\Entity\BlogComment;

use app\Lib\UserManager;

class MapsController extends Controller
{
    public function indexAction()
    {
        echo $this->get('template')->burn('maps', 'html');
    }
}
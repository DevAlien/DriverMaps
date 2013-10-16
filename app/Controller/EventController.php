<?php
namespace app\Controller;

use Aliegon\Controller\Controller;
use app\Entity\BlogComment;

use app\Lib\UserManager;

class EventController extends Controller
{
    public function viewAction()
    {
        $em = $this->get('em');
        $eventRepo = $em->getRepository('Cookmehome:Event');
        $event = $eventRepo->findOneById($this->params['id']);
        if(!$event)
            throw new \Exception('NO EVENT FOUND');

        $this->getTpl()->assign('event', $event);
        echo $this->get('template')->burn('listing', 'html');
    }

    public function searchAction()
    {
        echo $this->get('template')->burn('search', 'html');
    }
}
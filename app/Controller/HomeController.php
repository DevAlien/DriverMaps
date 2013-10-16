<?php
namespace app\Controller;

use Aliegon\Controller\Controller;
use app\Entity\BlogComment;

use app\Lib\UserManager;

class HomeController extends Controller
{
    public function indexAction()
    {
        echo $this->get('template')->burn('index', 'html');
    }

    public function searchAction()
    {
        echo $this->get('template')->burn('search', 'html');
    }

    public function retrieveAction()
    {
        $em = $this->get('em');
        $eventRepo = $em->getRepository('Qcino:Event');
        $events = $eventRepo->getAllEvents($this->params['minLat'], $this->params['maxLat'], $this->params['minLng'], $this->params['maxLng']);
        $arrayEvents = array();
        if(count($events) > 0) {

            foreach($events as $event) {
                $tmpArray = array();
                $tmpArray['title'] = $event->getTitle();
                $tmpArray['description'] = $event->getDescription();
                $tmpArray['thumb'] = $event->getPicture();
                $tmpArray['name'] = $event->getUser()->getUserData()->getName();
                $tmpArray['lat'] = $event->getLat();
                $tmpArray['lng'] = $event->getLng();

                $arrayEvents[] = $tmpArray;
            }
        }
        echo json_encode($arrayEvents);
       //['title 1', 46.177542, 8.812108, 1, 'thumb.jpg', 'Luiis Franceschi', '80', 'Today menu is: tartare with truffle, t-bone “Fassora” steak from Piemonte Italy with a white risotto, chocolate soufflè', '2', '4', '60', '123', '###', 'premium'],
        //print_r($eventRepo->getAllEvents($this->params['minLat'], $this->params['maxLat'], $this->params['minLng'], $this->params['maxLng']));
        exit;
        
    }
}
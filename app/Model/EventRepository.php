<?php
namespace app\Model;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function getAllEvents($minLat, $maxLat, $minLng, $maxLng)
    {
        if($maxLng < $minLng && $maxLng < 0)
            $maxLng = 180;
        if($minLng > $maxLng && $minLng > 0)
            $minLng = -180;
        $query = $this->_em->createQuery('SELECT e FROM app\Entity\Event e WHERE e.lat BETWEEN :minLat AND :maxLat AND e.lng BETWEEN :minLng AND :maxLng');
        $query->setParameters(array(
            'minLat' => $minLat,
            'maxLat' => $maxLat,
            'minLng' => $minLng,
            'maxLng' => $maxLng
        ));

        return $query->getResult();
    }
}
<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use UCP\AbsenceBundle\Entity\KVObject;

class SyncController extends Controller
{
    /**
     * @Secure("ROLE_ADMIN")
     * @Route("/sync", name="sync_index")
     * @Template
     */
    public function indexAction()
    {
        return array(
            'calendar_name' => $this->getValue('sync.calendar_name'),
            'account_email' => $this->getValue('sync.account_email')
        );
    }

    private function getValue($key)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->find('UCPAbsenceBundle:KVObject', $key);

        if (!$object) {
            $object = new KVObject($key, null);
            $em->persist($object);
            $em->flush();
        }

        return $object->getValue();
    }

    private function setValue($key, $value)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->find('UCPAbsenceBundle:KVObject', $key);

        if (!$object) {
            $object = new KVObject($key, $value);
            $em->persist($object);
        } else {
            $object->setValue($value);
        }

        $em->flush();
    }
}

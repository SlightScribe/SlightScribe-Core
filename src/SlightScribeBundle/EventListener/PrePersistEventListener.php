<?php

namespace SlightScribeBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\Run;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class PrePersistEventListener  {


    const MIN_LENGTH = 10;
    const MIN_LENGTH_BIG = 100;
    const MAX_LENGTH = 250;
    const LENGTH_STEP = 1;

    function PrePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof Run) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('SlightScribeBundle:Run');
                $idLen = self::MIN_LENGTH;
                $id =  \SlightScribeBundle\SlightScribeBundle::createKey(1,$idLen);
                while($manager->doesPublicIdExist($id, $entity->getProject())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id =  \SlightScribeBundle\SlightScribeBundle::createKey(1,$idLen);
                }
                $entity->setPublicId($id);
            }
        } else if ($entity instanceof ProjectVersion) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('SlightScribeBundle:ProjectVersion');
                $idLen = self::MIN_LENGTH;
                $id =  \SlightScribeBundle\SlightScribeBundle::createKey(1,$idLen);
                while($manager->doesPublicIdExist($id, $entity->getProject())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id =  \SlightScribeBundle\SlightScribeBundle::createKey(1,$idLen);
                }
                $entity->setPublicId($id);
            }
        }

    }

}

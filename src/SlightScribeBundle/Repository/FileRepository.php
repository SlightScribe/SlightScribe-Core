<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\ProjectVersion;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class FileRepository extends EntityRepository
{

    public function findByCommunication(Communication $communication)
    {

        return $this->getEntityManager()
            ->createQuery(
                ' SELECT f FROM SlightScribeBundle:File f ' .
                ' JOIN f.communicationsHasFile chf  '.
                ' WHERE chf.communication = :communication '
            )
            ->setParameter('communication', $communication)
            ->getResult();

    }

    public function findForAccessPoint(AccessPoint $accessPoint)
    {

        return $this->getEntityManager()
            ->createQuery(
                ' SELECT f FROM SlightScribeBundle:File f ' .
                ' JOIN f.accessPointHasFiles aphf  ' .
                ' WHERE aphf.accessPoint = :accessPoint '
            )
            ->setParameter('accessPoint', $accessPoint)
            ->getResult();

    }


}


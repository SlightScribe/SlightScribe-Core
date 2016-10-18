<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\ProjectVersion;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class FieldRepository extends EntityRepository
{

    public function getForAccessPoint(AccessPoint $accessPoint)
    {


        return $this->getEntityManager()
            ->createQuery(
                ' SELECT f FROM SlightScribeBundle:Field f ' .
                ' JOIN f.accessPointHasFields aphf  '.
                ' WHERE aphf.accessPoint = :accessPoint '
            )
            ->setParameter('accessPoint', $accessPoint)
            ->getResult();


    }


}


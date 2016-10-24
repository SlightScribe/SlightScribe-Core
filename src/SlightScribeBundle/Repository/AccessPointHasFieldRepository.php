<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\AccessPointHasField;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\ProjectVersion;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AccessPointHasFieldRepository extends EntityRepository
{

    function addFieldToAccessPoint(Field $field, AccessPoint $accessPoint) {

        $link = $this->findOneBy(array('field'=>$field, 'accessPoint'=>$accessPoint));

        if (!$link) {
            $link = new AccessPointHasField();
            $link->setAccessPoint($accessPoint);
            $link->setField($field);
            $this->getEntityManager()->persist($link);
            $this->getEntityManager()->flush($link);
        }


    }

    function removeFieldFromAccessPoint(Field $field, AccessPoint $accessPoint) {


        $link = $this->findOneBy(array('field'=>$field, 'accessPoint'=>$accessPoint));

        if ($link) {
            $this->getEntityManager()->remove($link);
            $this->getEntityManager()->flush($link);
        }

    }


}

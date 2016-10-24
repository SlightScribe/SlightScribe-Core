<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\AccessPointHasFile;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\ProjectVersion;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AccessPointHasFileRepository extends EntityRepository
{

    function addFileToAccessPoint(File $file, AccessPoint $accessPoint) {

        $link = $this->findOneBy(array('file'=>$file, 'accessPoint'=>$accessPoint));

        if (!$link) {
            $link = new AccessPointHasFile();
            $link->setAccessPoint($accessPoint);
            $link->setFile($file);
            $this->getEntityManager()->persist($link);
            $this->getEntityManager()->flush($link);
        }


    }

    function removeFileFromAccessPoint(File $file, AccessPoint $accessPoint) {


        $link = $this->findOneBy(array('file'=>$file, 'accessPoint'=>$accessPoint));

        if ($link) {
            $this->getEntityManager()->remove($link);
            $this->getEntityManager()->flush($link);
        }

    }


}

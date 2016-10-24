<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\CommunicationHasFile;
use SlightScribeBundle\Entity\File;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class CommunicationHasFileRepository extends EntityRepository
{

    function addFileToCommunication(File $file, Communication $communication) {

        $link = $this->findOneBy(array('file'=>$file, 'communication'=>$communication));

        if (!$link) {
            $link = new CommunicationHasFile();
            $link->setCommunication($communication);
            $link->setFile($file);
            $this->getEntityManager()->persist($link);
            $this->getEntityManager()->flush($link);
        }


    }

    function removeFileFromCommunication(File $file, Communication $communication) {


        $link = $this->findOneBy(array('file'=>$file, 'communication'=>$communication));

        if ($link) {
            $this->getEntityManager()->remove($link);
            $this->getEntityManager()->flush($link);
        }

    }


}

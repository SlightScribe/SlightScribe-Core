<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\ProjectVersion;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class CommunicationRepository extends EntityRepository
{

    public function getFirstForProjectVersion(ProjectVersion $projectVersion)
    {

        $data =  $this->getEntityManager()
            ->createQuery(
                ' SELECT pv FROM SlightScribeBundle:Communication pv ' .
                ' WHERE pv.projectVersion = :projectVersion ORDER BY pv.sequence ASC '
            )
            ->setMaxResults(1)
            ->setParameter('projectVersion', $projectVersion)
            ->getResult();

        return count($data) == 1 ? $data[0] : null;

    }

    public function getNextAfter(Communication $communication) {

        $data =  $this->getEntityManager()
            ->createQuery(
                ' SELECT c FROM SlightScribeBundle:Communication c ' .
                ' WHERE c.projectVersion = :projectVersion AND c.sequence > :sequence ORDER BY c.sequence ASC '
            )
            ->setMaxResults(1)
            ->setParameter('projectVersion', $communication->getProjectVersion())
            ->setParameter('sequence', $communication->getSequence())
            ->getResult();

        return count($data) == 1 ? $data[0] : null;

    }

}


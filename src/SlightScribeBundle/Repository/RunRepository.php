<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\Project;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunRepository extends EntityRepository
{


    public function doesPublicIdExist($id, Project $project)
    {
        if ($project->getId()) {
            $s = $this->getEntityManager()
                ->createQuery(
                    ' SELECT pr FROM SlightScribeBundle:Run pr ' .
                    ' WHERE pr.project = :project AND pr.publicId = :public_id'
                )
                ->setParameter('project', $project)
                ->setParameter('public_id', $id)
                ->getResult();

            return (boolean)$s;
        } else {
            return false;
        }
    }


    public function getProjectRunsWithNoProjectRunLetters() {
        return $this->getEntityManager()
            ->createQuery(
                ' SELECT pr FROM SlightScribeBundle:Run pr  ' .
                ' LEFT JOIN pr.runCommunications prl '.
                ' WHERE prl.communication IS NULL'
            )
            ->getResult();
    }

    public function getActiveProjectRunsWithAtLeastOneRunCommunication() {
        return $this->getEntityManager()
            ->createQuery(
                ' SELECT pr FROM SlightScribeBundle:Run pr  ' .
                ' JOIN pr.runCommunications prl '.
                ' WHERE pr.finishedNaturallyAt IS NULL AND pr.stoppedManuallyAt IS NULL '.
                ' GROUP BY pr.id'
            )
            ->getResult();
    }

}


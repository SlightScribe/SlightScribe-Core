<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\Project;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProjectVersionRepository extends EntityRepository
{


    public function doesPublicIdExist($id, Project $project)
    {
        if ($project->getId()) {
            $s = $this->getEntityManager()
                ->createQuery(
                    ' SELECT pv FROM SlightScribeBundle:ProjectVersion pv ' .
                    ' WHERE pv.project = :project AND pv.publicId = :public_id'
                )
                ->setParameter('project', $project)
                ->setParameter('public_id', $id)
                ->getResult();

            return (boolean)$s;
        } else {
            return false;
        }
    }


    public function findPublishedVersionForProject(Project $project)
    {
        $tvps =  $this->getEntityManager()
            ->createQuery(
                ' SELECT pvp FROM SlightScribeBundle:ProjectVersionPublished pvp'.
                ' JOIN pvp.projectVersion pv '.
                ' WHERE    pv.project = :project '.
                ' ORDER BY pvp.publishedAt DESC '.
                '  '
            )
            ->setMaxResults(1)
            ->setParameter('project', $project)
            ->getResult();
        if ($tvps) {
            return $tvps[0]->getProjectVersion();
        } else {
            return null;
        }
    }


    public function findLatestVersionForProject(Project $project)
    {
        $tvps =  $this->getEntityManager()
            ->createQuery(
                ' SELECT pv FROM SlightScribeBundle:ProjectVersion pv'.
                ' WHERE    pv.project = :project '.
                ' ORDER BY pv.createdAt DESC '.
                '  '
            )
            ->setMaxResults(1)
            ->setParameter('project', $project)
            ->getResult();
        if ($tvps) {
            return $tvps[0];
        } else {
            return null;
        }
    }


}


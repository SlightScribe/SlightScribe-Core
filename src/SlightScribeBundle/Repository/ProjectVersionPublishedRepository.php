<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\Project;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProjectVersionPublishedRepository extends EntityRepository
{



    public function findAllForProject(Project $project)
    {
        return $this->getEntityManager()
            ->createQuery(
                ' SELECT pvp FROM SlightScribeBundle:ProjectVersionPublished pvp '.
                ' JOIN pvp.projectVersion pv '.
                ' WHERE pv.project = :project '.
                ' ORDER BY pvp.publishedAt ASC'
            )
            ->setParameter('project', $project)
            ->getResult();
    }





}
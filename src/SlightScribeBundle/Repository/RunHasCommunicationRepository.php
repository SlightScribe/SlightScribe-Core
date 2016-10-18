<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\Run;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunHasCommunicationRepository extends EntityRepository
{

    public function getLastForRun(Run $run)
    {

        $data =  $this->getEntityManager()
            ->createQuery(
                ' SELECT rc FROM SlightScribeBundle:RunHasCommunication rc ' .
                ' JOIN rc.communication c '.
                ' WHERE rc.run = :run ORDER BY c.sequence DESC '
            )
            ->setMaxResults(1)
            ->setParameter('run', $run)
            ->getResult();

        return count($data) == 1 ? $data[0] : null;

    }


}


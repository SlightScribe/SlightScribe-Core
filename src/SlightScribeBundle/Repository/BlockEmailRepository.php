<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\ProjectVersion;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class BlockEmailRepository extends EntityRepository
{

    public function isEmailBlocked($email)
    {

        $results = $this->getEntityManager()
            ->createQuery(
                ' SELECT be FROM SlightScribeBundle:BlockEmail be ' .
                ' WHERE be.emailClean = :email AND be.startedAt <= :now AND ( be.finishedAt IS NULL OR be.finishedAt >= :now ) '
            )
            ->setParameter('email', $email)
            ->setParameter('now', new \DateTime())
            ->getResult();

        return (boolean)$results;


    }

    public function countRunsFromEmailInLastSeconds($email, $seconds=60) {

        $after = new \DateTime();
        $after->sub(new \DateInterval('PT'.$seconds. 'S'));

        $results = $this->getEntityManager()
            ->createQuery(
                ' SELECT r FROM SlightScribeBundle:Run r ' .
                ' WHERE r.emailClean = :email AND r.createdAt >= :after '
            )
            ->setParameter('email', $email)
            ->setParameter('after', $after)
            ->getResult();

        return count($results);

    }


}


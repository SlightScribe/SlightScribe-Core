<?php

namespace SlightScribeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SlightScribeBundle\Entity\ProjectVersion;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class BlockIPRepository extends EntityRepository
{

    public function isAddressBlocked($ip)
    {

        $results = $this->getEntityManager()
            ->createQuery(
                ' SELECT bip FROM SlightScribeBundle:BlockIP bip ' .
                ' WHERE bip.ip = :ip AND bip.startedAt <= :now AND ( bip.finishedAt IS NULL OR bip.finishedAt >= :now ) '
            )
            ->setParameter('ip', $ip)
            ->setParameter('now', new \DateTime())
            ->getResult();

        return (boolean)$results;


    }

    public function countRunsFromIPInLastSeconds($ip, $seconds=60) {

        $after = new \DateTime();
        $after->sub(new \DateInterval('PT'.$seconds. 'S'));

        $results = $this->getEntityManager()
            ->createQuery(
                ' SELECT r FROM SlightScribeBundle:Run r ' .
                ' WHERE r.createdByIp = :ip AND r.createdAt >= :after '
            )
            ->setParameter('ip', $ip)
            ->setParameter('after', $after)
            ->getResult();

        return count($results);

    }


}


<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="block_ip")
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\BlockIPRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class BlockIP {


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="ip", type="string", length=250, nullable=false)
     * @Assert\NotBlank()
     */
    private $ip;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=false)
     */
    private $startedAt;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    private $finishedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return datetime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param datetime $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return datetime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @param datetime $finishedAt
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;
    }





    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->startedAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}




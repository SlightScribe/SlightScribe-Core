<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="run_used_access_point")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunUsedAccessPoint {

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\AccessPoint")
     * @ORM\JoinColumn(name="access_point_id", referencedColumnName="id", nullable=false)
     */
    private $accessPoint;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Run")
     * @ORM\JoinColumn(name="run_id", referencedColumnName="id", nullable=false)
     */
    private $run;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @return mixed
     */
    public function getAccessPoint()
    {
        return $this->accessPoint;
    }

    /**
     * @param mixed $accessPoint
     */
    public function setAccessPoint($accessPoint)
    {
        $this->accessPoint = $accessPoint;
    }

    /**
     * @return mixed
     */
    public function getRun()
    {
        return $this->run;
    }

    /**
     * @param mixed $run
     */
    public function setRun($run)
    {
        $this->run = $run;
    }

    /**
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}

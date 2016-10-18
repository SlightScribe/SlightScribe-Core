<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="project_version_has_default_access_point")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProjectVersionHasDefaultAccessPoint
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\ProjectVersion")
     * @ORM\JoinColumn(name="project_version_id", referencedColumnName="id", nullable=false)
     */
    private $projectVersion;

    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\AccessPoint")
     * @ORM\JoinColumn(name="access_point_id", referencedColumnName="id", nullable=false)
     */
    private $accessPoint;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @return mixed
     */
    public function getProjectVersion()
    {
        return $this->projectVersion;
    }

    /**
     * @param mixed $projectVersion
     */
    public function setProjectVersion($projectVersion)
    {
        $this->projectVersion = $projectVersion;
    }

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

<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="access_point_has_file")
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\AccessPointHasFileRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AccessPointHasFile {



    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\AccessPoint")
     * @ORM\JoinColumn(name="access_point_id", referencedColumnName="id", nullable=false)
     */
    private $accessPoint;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    private $file;



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
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
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

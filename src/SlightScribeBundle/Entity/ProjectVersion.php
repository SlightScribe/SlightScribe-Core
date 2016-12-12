<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="project_version", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_id", "public_id"})})
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\ProjectVersionRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProjectVersion {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;



    /**
     * @ORM\Column(name="public_id", type="string", length=250, unique=false, nullable=false)
     * @Assert\NotBlank()
     */
    private $publicId;



    /**
     * @var string
     *
     * @ORM\Column(name="title_admin", type="string", length=250, nullable=false)
     */
    private $titleAdmin;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_user_to_after_manual_stop", type="text", nullable=true)
     */
    private $redirectUserToAfterManualStop;


    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\ProjectVersion")
     * @ORM\JoinColumn(name="from_old_version_id", referencedColumnName="id", nullable=true)
     */
    private $fromOldVersion;


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
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }



    /**
     * @return mixed
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param mixed $publicId
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
    }

    /**
     * @return string
     */
    public function getTitleAdmin()
    {
        return $this->titleAdmin;
    }

    /**
     * @param string $titleAdmin
     */
    public function setTitleAdmin($titleAdmin)
    {
        $this->titleAdmin = $titleAdmin;
    }

    /**
     * @return string
     */
    public function getRedirectUserToAfterManualStop()
    {
        return $this->redirectUserToAfterManualStop;
    }

    /**
     * @param string $redirectUserToAfterManualStop
     */
    public function setRedirectUserToAfterManualStop($redirectUserToAfterManualStop)
    {
        $this->redirectUserToAfterManualStop = $redirectUserToAfterManualStop;
    }

    /**
     * @return mixed
     */
    public function getFromOldVersion()
    {
        return $this->fromOldVersion;
    }

    /**
     * @param mixed $fromOldVersion
     */
    public function setFromOldVersion($fromOldVersion)
    {
        $this->fromOldVersion = $fromOldVersion;
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
        if (!$this->titleAdmin) {
            $this->titleAdmin = 'v1';
        }
    }


}

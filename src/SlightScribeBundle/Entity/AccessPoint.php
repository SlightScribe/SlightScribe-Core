<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="access_point", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_version_id", "public_id"})})
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AccessPoint
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;


    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\ProjectVersion")
     * @ORM\JoinColumn(name="project_version_id", referencedColumnName="id", nullable=false)
     */
    private $projectVersion;

    /**
     * @ORM\Column(name="public_id", type="string", length=250, unique=false, nullable=false)
     * @Assert\NotBlank()
     */
    private $publicId;


    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Communication")
     * @ORM\JoinColumn(name="communication_id", referencedColumnName="id", nullable=false)
     */
    private $communication;


    /**
     * @var string
     *
     * @ORM\Column(name="title_admin", type="string", length=250, nullable=false)
     */
    private $titleAdmin;


    /**
     * @ORM\Column(name="form", type="text", nullable=true)
     */
    private $form;

    public function copyFromOld(AccessPoint $oldAccessPoint) {
        $this->publicId = $oldAccessPoint->publicId;
        $this->titleAdmin = $oldAccessPoint->titleAdmin;
        $this->form = $oldAccessPoint->form;
    }

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
    public function getCommunication()
    {
        return $this->communication;
    }

    /**
     * @param mixed $communication
     */
    public function setCommunication($communication)
    {
        $this->communication = $communication;
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
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param string $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
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
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }








    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
    }




}

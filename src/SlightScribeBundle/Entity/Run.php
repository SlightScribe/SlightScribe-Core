<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use SlightScribeBundle\SlightScribeBundle;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="run", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_id", "public_id"})})
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\RunRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class Run {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Need to store Project so can have DB enforce unique public ID
     *
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

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
     * @ORM\Column(name="security_key", type="string", length=250, nullable=false)
     * @Assert\NotBlank()
     */
    private $securityKey;

    /**
     * @ORM\Column(name="email", type="string", length=250, nullable=false)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(name="email_clean", type="string", length=250, nullable=false)
     * @Assert\NotBlank()
     */
    private $emailClean;


    /**
     * @ORM\Column(name="created_by_ip", type="string", length=250, nullable=false)
     * @Assert\NotBlank()
     */
    private $createdByIp;


    /**
     * @ORM\OneToMany(targetEntity="SlightScribeBundle\Entity\RunHasCommunication", mappedBy="run")
     */
    private $runCommunications;


    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var datetime $finishedNaturallyAt
     *
     * @ORM\Column(name="finished_naturally_at", type="datetime", nullable=true)
     */
    private $finishedNaturallyAt;

    /**
     * @var datetime $stoppedManuallyAt
     *
     * @ORM\Column(name="stopped_manually_at", type="datetime", nullable=true)
     */
    private $stoppedManuallyAt;

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
    public function getSecurityKey()
    {
        return $this->securityKey;
    }

    /**
     * @param mixed $securityKey
     */
    public function setSecurityKey($securityKey)
    {
        $this->securityKey = $securityKey;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->emailClean = $email;
    }

    /**
     * @return mixed
     */
    public function getEmailClean()
    {
        return $this->emailClean;
    }

    /**
     * @param mixed $emailClean
     */
    public function setEmailClean($emailClean)
    {
        $this->emailClean = $emailClean;
    }



    /**
     * @return mixed
     */
    public function getCreatedByIp()
    {
        return $this->createdByIp;
    }

    /**
     * @param mixed $createdByIp
     */
    public function setCreatedByIp($createdByIp)
    {
        $this->createdByIp = $createdByIp;
    }

    /**
     * @return datetime
     */
    public function getFinishedNaturallyAt()
    {
        return $this->finishedNaturallyAt;
    }

    /**
     * @param datetime $finishedNaturallyAt
     */
    public function setFinishedNaturallyAt($finishedNaturallyAt)
    {
        $this->finishedNaturallyAt = $finishedNaturallyAt;
    }

    /**
     * @return datetime
     */
    public function getStoppedManuallyAt()
    {
        return $this->stoppedManuallyAt;
    }

    /**
     * @param datetime $stoppedManuallyAt
     */
    public function setStoppedManuallyAt($stoppedManuallyAt)
    {
        $this->stoppedManuallyAt = $stoppedManuallyAt;
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
    public function getProjectRunLetters()
    {
        return $this->runCommunications;
    }




    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
        $this->securityKey = SlightScribeBundle::createKey(10, 100);
    }


}

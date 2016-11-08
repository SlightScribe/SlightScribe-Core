<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="communication", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_version_id", "public_id"})})
 *
 * We don't do
 *    UniqueConstraint(name="sequence", columns={"project_version_id", "sequence"})
 * because later we may want a feature where there are 2 emails for a sequence point - one for if the user goes to an access point and one to chase the user later if they donat
 *
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\CommunicationRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class Communication {

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
     * @ORM\Column(name="sequence", type="smallint", nullable=false)
     */
    private $sequence;


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
     * @ORM\Column(name="days_before", type="smallint", nullable=true)
     */
    private $days_before = 0;



    /**
     * @ORM\Column(name="email_content_text_template", type="text", nullable=true)
     */
    private $emailContentTextTemplate;

    /**
     * @ORM\Column(name="email_content_html_template", type="text", nullable=true)
     */
    private $emailContentHTMLTemplate;

    /**
     * @ORM\Column(name="email_subject_template", type="text", nullable=true)
     */
    private $emailSubjectTemplate;


    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Communication")
     * @ORM\JoinColumn(name="from_old_version_id", referencedColumnName="id", nullable=true)
     */
    private $fromOldVersion;



    public function copyFromOld(Communication $communication) {
        $this->fromOldVersion = $communication;
        $this->sequence = $communication->sequence;
        $this->publicId = $communication->publicId;
        $this->titleAdmin = $communication->titleAdmin;
        $this->days_before = $communication->days_before;
        $this->emailContentTextTemplate = $communication->emailContentTextTemplate;
        $this->emailContentHTMLTemplate = $communication->emailContentHTMLTemplate;
        $this->emailSubjectTemplate = $communication->emailSubjectTemplate;
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
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param mixed $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
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
     * @return mixed
     */
    public function getDaysBefore()
    {
        return $this->days_before;
    }

    /**
     * @param mixed $days_before
     */
    public function setDaysBefore($days_before)
    {
        $this->days_before = $days_before;
    }

    /**
     * @return mixed
     */
    public function getEmailContentTextTemplate()
    {
        return $this->emailContentTextTemplate;
    }

    /**
     * @param mixed $emailContentTextTemplate
     */
    public function setEmailContentTextTemplate($emailContentTextTemplate)
    {
        $this->emailContentTextTemplate = $emailContentTextTemplate;
    }

    /**
     * @return mixed
     */
    public function getEmailContentHTMLTemplate()
    {
        return $this->emailContentHTMLTemplate;
    }

    /**
     * @param mixed $emailContentHTMLTemplate
     */
    public function setEmailContentHTMLTemplate($emailContentHTMLTemplate)
    {
        $this->emailContentHTMLTemplate = $emailContentHTMLTemplate;
    }


    /**
     * @return mixed
     */
    public function getEmailSubjectTemplate()
    {
        return $this->emailSubjectTemplate;
    }

    /**
     * @param mixed $emailSubjectTemplate
     */
    public function setEmailSubjectTemplate($emailSubjectTemplate)
    {
        $this->emailSubjectTemplate = $emailSubjectTemplate;
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
    }


}

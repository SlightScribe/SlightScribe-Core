<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="file", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_version_id", "public_id"})})
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class File {

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
     * @var string
     *
     * @ORM\Column(name="title_admin", type="string", length=250, nullable=false)
     */
    private $titleAdmin;


    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=250, nullable=false)
     */
    private $filename;


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=250, nullable=false)
     */
    private $type = "LETTER_TEXT";

    /**
     * @ORM\Column(name="letter_content_template", type="text", nullable=true)
     */
    private $letterContentTemplate;


    /**
     * @ORM\OneToMany(targetEntity="SlightScribeBundle\Entity\CommunicationHasFile", mappedBy="file")
     */
    private $communicationsHasFile;


    /**
     * @ORM\OneToMany(targetEntity="SlightScribeBundle\Entity\AccessPointHasFile", mappedBy="file")
     */
    private $accessPointHasFiles;

    public function copyFromOld(File $file) {
        $this->publicId = $file->publicId;;
        $this->titleAdmin = $file->titleAdmin;
        $this->filename = $file->filename;
        $this->type = $file->type;
        $this->letterContentTemplate = $file->letterContentTemplate;
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
    public function getLetterContentTemplate()
    {
        return $this->letterContentTemplate;
    }

    /**
     * @param mixed $letterContentTemplate
     */
    public function setLetterContentTemplate($letterContentTemplate)
    {
        $this->letterContentTemplate = $letterContentTemplate;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
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
    public function getType()
    {
        return $this->type;
    }

    public function isTypeLetterText() {
        return $this->type == 'LETTER_TEXT';
    }

    public function isTypeLetterPdf() {
        return $this->type == 'LETTER_PDF';
    }

    public function getContentType() {
        if ($this->isTypeLetterPdf()) {
            return 'application/pdf';
        } else if ($this->isTypeLetterText()) {
            return 'text/plain';
        }
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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

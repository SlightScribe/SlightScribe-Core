<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="field", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_id", "public_id"})})
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\FieldRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class Field {

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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=250, nullable=false)
     */
    private $type = self::TYPE_TEXT;


    const TYPE_TEXT = "TEXT";
    const TYPE_TEXTAREA = "TEXTAREA";
    const TYPE_DATE = "DATE";


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
     * @ORM\Column(name="sort", type="smallint", nullable=false)
     */
    private $sort = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=250, nullable=false)
     */
    private $label;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @ORM\OneToMany(targetEntity="SlightScribeBundle\Entity\AccessPointHasField", mappedBy="field")
     */
    private $accessPointHasFields;

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
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function isTypeText() {
        return strtolower($this->type) == strtolower(self::TYPE_TEXT);
    }

    public function isTypeTextArea() {
        return strtolower($this->type) == strtolower(self::TYPE_TEXTAREA);
    }

    public function isTypeDate() {
        return strtolower($this->type) == strtolower(self::TYPE_DATE);
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getLetterVersionsHaveField()
    {
        return $this->letterVersionsHaveField;
    }

    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}

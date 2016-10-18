<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="project_version_published")
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\ProjectVersionPublishedRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProjectVersionPublished {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\ProjectVersion")
     * @ORM\JoinColumn(name="project_version_id", referencedColumnName="id", nullable=false)
     */
    private $projectVersion;


    /**
     *
     * @ORM\Column(name="published_at", type="datetime", nullable=false)
     */
    private $publishedAt;


    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\User")
     * @ORM\JoinColumn(name="published_by_id", referencedColumnName="id", nullable=true)
     */
    private $publishedBy;


    /**
     * @var string
     *
     * @ORM\Column(name="comment_published_admin", type="text", nullable=true)
     */
    private $commentPublishedAdmin;

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
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @param mixed $publishedAt
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return mixed
     */
    public function getPublishedBy()
    {
        return $this->publishedBy;
    }

    /**
     * @param mixed $publishedBy
     */
    public function setPublishedBy($publishedBy)
    {
        $this->publishedBy = $publishedBy;
    }

    /**
     * @return string
     */
    public function getCommentPublishedAdmin()
    {
        return $this->commentPublishedAdmin;
    }

    /**
     * @param string $commentPublishedAdmin
     */
    public function setCommentPublishedAdmin($commentPublishedAdmin)
    {
        $this->commentPublishedAdmin = $commentPublishedAdmin;
    }




    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->publishedAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}

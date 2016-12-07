<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="run_has_file")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunHasFile extends BaseRunFile {



    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Run")
     * @ORM\JoinColumn(name="run_id", referencedColumnName="id", nullable=false)
     */
    private $run;



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
     * @var datetime $createdAt
     *
     * @ORM\Column(name="sent_at", type="datetime", nullable=true)
     */
    private $sentAt;

    /**
     * @ORM\Column(name="letter_content_header_right", type="text", nullable=true)
     */
    private $letterContentHeaderRight;

    /**
     * @ORM\Column(name="letter_content", type="text", nullable=true)
     */
    private $letterContent;



    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=250, nullable=true)
     */
    private $filename;

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
     * @return datetime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @param datetime $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return mixed
     */
    public function getLetterContent()
    {
        return $this->letterContent;
    }

    /**
     * @param mixed $letterContent
     */
    public function setLetterContent($letterContent)
    {
        $this->letterContent = $letterContent;
    }

    /**
     * @return mixed
     */
    public function getLetterContentHeaderRight()
    {
        return $this->letterContentHeaderRight;
    }

    /**
     * @param mixed $letterContentHeaderRight
     */
    public function setLetterContentHeaderRight($letterContentHeaderRight)
    {
        $this->letterContentHeaderRight = $letterContentHeaderRight;
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
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}

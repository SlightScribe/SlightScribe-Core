<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="file_template_error")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class FileTemplateError {

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
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Run")
     * @ORM\JoinColumn(name="run_id", referencedColumnName="id", nullable=true)
     */
    private $run;

    /**
     * @ORM\Column(name="letter_content_template", type="text", nullable=true)
     */
    private $letterContentTemplate;

    /**
     * @ORM\Column(name="twig_variables", type="text", nullable=true)
     */
    private $twigVariables;

    /**
     * @ORM\Column(name="error_message", type="text", nullable=true)
     */
    private $errorMessage;

    /**
     * @ORM\Column(name="error_code", type="integer", nullable=true)
     */
    private $errorCode;

    /**
     * @ORM\Column(name="error_file", type="text", nullable=true)
     */
    private $errorFile;

    /**
     * @ORM\Column(name="error_line", type="integer", nullable=true)
     */
    private $errorLine;

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
    public function getTwigVariables()
    {
        return $this->twigVariables;
    }

    /**
     * @param mixed $twigVariables
     */
    public function setTwigVariables($twigVariables)
    {
        $this->twigVariables = $twigVariables;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return mixed
     */
    public function getErrorFile()
    {
        return $this->errorFile;
    }

    /**
     * @param mixed $errorFile
     */
    public function setErrorFile($errorFile)
    {
        $this->errorFile = $errorFile;
    }

    /**
     * @return mixed
     */
    public function getErrorLine()
    {
        return $this->errorLine;
    }

    /**
     * @param mixed $errorLine
     */
    public function setErrorLine($errorLine)
    {
        $this->errorLine = $errorLine;
    }

    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}

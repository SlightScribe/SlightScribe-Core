<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="communication_template_error")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class CommunicationTemplateError {

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
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Communication")
     * @ORM\JoinColumn(name="communication_id", referencedColumnName="id", nullable=false)
     */
    private $communication;

    /**
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Run")
     * @ORM\JoinColumn(name="run_id", referencedColumnName="id", nullable=true)
     */
    private $run;

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

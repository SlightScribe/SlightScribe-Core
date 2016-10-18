<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="run_has_communication")
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\RunHasCommunicationRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunHasCommunication {



    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Run")
     * @ORM\JoinColumn(name="run_id", referencedColumnName="id", nullable=false)
     */
    private $run;


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Communication")
     * @ORM\JoinColumn(name="communication_id", referencedColumnName="id", nullable=false)
     */
    private $communication;



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
     * @ORM\Column(name="email_content_text", type="text", nullable=true)
     */
    private $emailContentText;

    /**
     * @ORM\Column(name="email_content_html", type="text", nullable=true)
     */
    private $emailContentHTML;

    /**
     * @ORM\Column(name="email_subject", type="text", nullable=true)
     */
    private $emailSubject;


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
    public function getEmailContentText()
    {
        return $this->emailContentText;
    }

    /**
     * @param mixed $emailContentText
     */
    public function setEmailContentText($emailContentText)
    {
        $this->emailContentText = $emailContentText;
    }

    /**
     * @return mixed
     */
    public function getEmailContentHTML()
    {
        return $this->emailContentHTML;
    }

    /**
     * @param mixed $emailContentHTML
     */
    public function setEmailContentHTML($emailContentHTML)
    {
        $this->emailContentHTML = $emailContentHTML;
    }




    /**
     * @return mixed
     */
    public function getEmailSubject()
    {
        return $this->emailSubject;
    }

    /**
     * @param mixed $emailSubject
     */
    public function setEmailSubject($emailSubject)
    {
        $this->emailSubject = $emailSubject;
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

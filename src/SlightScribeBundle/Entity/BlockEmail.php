<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="block_email")
 * @ORM\Entity(repositoryClass="SlightScribeBundle\Repository\BlockEmailRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class BlockEmail {


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var datetime $createdAt
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=false)
     */
    private $startedAt;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    private $finishedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return datetime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param datetime $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return datetime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @param datetime $finishedAt
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;
    }





    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->startedAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}




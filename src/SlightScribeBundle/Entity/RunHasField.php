<?php

namespace SlightScribeBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="run_has_field")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunHasField {

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Run")
     * @ORM\JoinColumn(name="run_id", referencedColumnName="id", nullable=false)
     */
    private $run;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SlightScribeBundle\Entity\Field")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id", nullable=false)
     */
    private $field;



    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;


    /**
     * @ORM\Column(name="value", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $value;

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
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */




    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Variant
 *
 * @ORM\Table(name="variant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VariantRepository")
 */
class Variant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="is_right", type="string", length=255)
     */
    private $isRight;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="variants")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    private $question;

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Variant
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set isRight
     *
     * @param string $isRight
     *
     * @return Variant
     */
    public function setIsRight($isRight)
    {
        $this->isRight = $isRight;

        return $this;
    }

    /**
     * Get isRight
     *
     * @return string
     */
    public function getIsRight()
    {
        return $this->isRight;
    }
}


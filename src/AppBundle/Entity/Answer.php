<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Entity
 * @ORM\Table(name="answer")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "text" = "TextAnswer",
 *   "decimal" = "DecimalAnswer",
 *   "variant" = "VariantAnswer"
 * })
 */
abstract class Answer
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
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    private $question;

    /**
     * @var Attempt
     *
     * @ORM\ManyToOne(targetEntity="Attempt", inversedBy="answers")
     * @ORM\JoinColumn(name="attempt_id", referencedColumnName="id", nullable=false)
     */
    private $attempt;

    /**
     * @return Attempt
     */
    public function getAttempt()
    {
        return $this->attempt;
    }

    /**
     * @param Attempt $attempt
     */
    public function setAttempt($attempt)
    {
        $this->attempt = $attempt;
    }

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Entity
 * @ORM\Table(name="question")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "text" = "QuestionWithTextAnswer",
 *   "decimal" = "QuestionWithDecimalAnswer",
 *   "single" = "QuestionWithSingleCorrectAnswer",
 *   "multiple" = "QuestionWithMultipleCorrectAnswers"
 * })
 */
abstract class Question
{
    const TEXT = 'text';
    const DECIMAL = 'decimal';
    const SINGLE = 'single';
    const MULTIPLE = 'multiple';

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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence_number", type="integer")
     */
    private $sequenceNumber;

    /**
     * @var Test
     *
     * @ORM\ManyToOne(targetEntity="Test", inversedBy="questions")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id", nullable=false)
     */
    private $test;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Variant", mappedBy="question")
     */
    private $variants;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     */
    private $answers;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getChoices()
    {
        if ($this->variants->toArray() === []) {
            return [];
        }
        $variants = $this->getVariants()->toArray();
        $choices = [];
        foreach ($variants as $variant) {
            $choices[$variant->getValue()] = $variant->getId();
        }
        return $choices;
    }

    /**
     * This method is some sort of crutch to get access to the Question type
     * from php/view
     *
     * @return string
     */
    public function getType()
    {
        $map = [
                self::TEXT => "QuestionWithTextAnswer",
                self::DECIMAL => "QuestionWithDecimalAnswer",
                self::SINGLE => "QuestionWithSingleCorrectAnswer",
                self::MULTIPLE => "QuestionWithMultipleCorrectAnswers"
        ];
        $spaces = explode("\\", get_class($this));
        $class = end($spaces);
        return array_search($class, $map);
    }

    /**
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return ArrayCollection
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param array $variants
     */
    public function setVariants($variants)
    {
        foreach ($variants as $variant) {
            $this->variants[] = $variant;
        }
    }

    /**
     * @return Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param Test $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int $sequenceNumber
     */
    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }
}

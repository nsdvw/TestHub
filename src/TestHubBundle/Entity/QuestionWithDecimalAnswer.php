<?php
namespace TestHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class QuestionWithDecimalAnswer extends Question
{
    /**
     * @var string
     *
     * @ORM\Column(name="answer_decimal", type="string", nullable=true)
     */
    private $answerDecimal;

    /**
     * @var int
     *
     * @ORM\Column(name="`precision`", type="integer", nullable=true)
     */
    private $precision;

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;
    }

    /**
     * @return string
     */
    public function getAnswerDecimal()
    {
        return $this->answerDecimal;
    }

    /**
     * @param string $answerDecimal
     */
    public function setAnswerDecimal($answerDecimal)
    {
        $this->answerDecimal = $answerDecimal;
    }
}

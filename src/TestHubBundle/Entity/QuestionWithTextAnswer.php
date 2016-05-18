<?php
namespace TestHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class QuestionWithTextAnswer extends Question
{
    /**
     * @var string
     *
     * @ORM\Column(name="answer_text", type="string", nullable=true)
     */
    private $answerText;

    /**
     * @return string
     */
    public function getAnswerText()
    {
        return $this->answerText;
    }

    /**
     * @param string $answerText
     */
    public function setAnswerText($answerText)
    {
        $this->answerText = $answerText;
    }
}

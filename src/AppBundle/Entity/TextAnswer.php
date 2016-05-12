<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TextAnswer extends Answer
{
    /**
     * @var string
     *
     * @ORM\Column(name="text_answer", type="string")
     */
    private $textAnswer;

    /**
     * @return string
     */
    public function getTextAnswer()
    {
        return $this->textAnswer;
    }

    /**
     * @param string $textAnswer
     */
    public function setTextAnswer($textAnswer)
    {
        $this->textAnswer = $textAnswer;
    }
}

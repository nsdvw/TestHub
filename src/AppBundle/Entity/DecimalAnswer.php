<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DecimalAnswer extends Answer
{
    /**
     * @var string
     *
     * @ORM\Column(name="decimal_answer", type="string")
     */
    private $decimalAnswer;

    /**
     * @return string
     */
    public function getDecimalAnswer()
    {
        return $this->decimalAnswer;
    }

    /**
     * @param string $decimalAnswer
     */
    public function setDecimalAnswer($decimalAnswer)
    {
        $this->decimalAnswer = $decimalAnswer;
    }
}

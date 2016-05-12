<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Attempt
 *
 * @ORM\Table(name="attempt")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttemptRepository")
 */
class Attempt
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
     * @var \DateTime
     *
     * @ORM\Column(name="started", type="datetime")
     */
    private $started;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="attempts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $trier;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="attempt")
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param array $answers
     */
    public function setAnswers($answers)
    {
        foreach ($answers as $answer) {
            $this->answers[] = $answer;
        }
    }

    /**
     * @return User
     */
    public function getTrier()
    {
        return $this->trier;
    }

    /**
     * @param User $trier
     */
    public function setTrier($trier)
    {
        $this->trier = $trier;
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
     * Set started
     *
     * @param \DateTime $started
     *
     * @return Attempt
     */
    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Get started
     *
     * @return \DateTime
     */
    public function getStarted()
    {
        return $this->started;
    }
}


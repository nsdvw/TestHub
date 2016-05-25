<?php
namespace TestHubBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Attempt
 *
 * @ORM\Table(name="attempt")
 * @ORM\Entity(repositoryClass="TestHubBundle\Repository\AttemptRepository")
 */
class Attempt
{
    const INFINITE = -1;

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
     * @var string
     *
     * @ORM\Column(name="status", type="string", options={"default"="active"})
     */
    private $status = "active";

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="attempts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $trier;

    /**
     * @var Test
     *
     * @ORM\ManyToOne(targetEntity="Test", inversedBy="attempts")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id", nullable=false)
     */
    private $test;

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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getTimeLeft()
    {
        $now = new \DateTime();
        $immutable = new \DateTimeImmutable();
        $started = $immutable->setTimestamp($this->started->getTimestamp());
        $limit = $this->getTest()->getTimeLimit();
        if ($limit === 0) {
            return self::INFINITE;
        }
        $interval = new \DateInterval("PT{$limit}M");
        $expire = $started->add($interval);
        $diff = $expire->getTimestamp() - $now->getTimestamp();
        if ($diff <= 0) {
            return 0;
        }
        return $diff;
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
     * Get started
     *
     * @return \DateTime
     */
    public function getStarted()
    {
        return $this->started;
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
}


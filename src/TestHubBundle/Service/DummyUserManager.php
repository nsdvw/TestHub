<?php
namespace TestHubBundle\Service;

use TestHubBundle\Entity\Attempt;
use TestHubBundle\Entity\User;
use TestHubBundle\Helper\StringGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Temporary class for user management
 */
class DummyUserManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param Attempt $attempt
     * @return bool
     */
    public function hasRights(User $user, Attempt $attempt)
    {
        return $user->getId() === $attempt->getTrier()->getId();
    }

    /**
     * @param Request $request
     * @return User|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getUser(Request $request)
    {
        $id = $request->cookies->get('id');
        $accessToken = $request->cookies->get('accessToken');
        $user = $this->em->find('TestHubBundle:User', intval($id));
        if ($user) {
            if ($user->getAccessToken() === $accessToken) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @return User
     */
    public function createGuestUser()
    {
        $user = new User();
        $token = StringGenerator::generateString();
        $user->setAccessToken($token);

        return $user;
    }

    /**
     * @param User $user
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(User $user)
    {
        $sql = "INSERT INTO user (name, salt, hash, email, login, accessToken)
                VALUES (:name, :salt, :hash, :email, :login, :accessToken)";
        $statement = $this->em->getConnection()->prepare($sql);
        $statement->execute([
            'name' => $user->getName(),
            'salt' => $user->getSalt(),
            'hash' => $user->getHash(),
            'email' => $user->getEmail(),
            'login' => $user->getLogin(),
            'accessToken' => $user->getAccessToken(),
        ]);

        return intval($this->em->getConnection()->lastInsertId());
    }

    /**
     * @param Response $response
     * @param User $user
     */
    public function loginGuestUser(Response $response, User $user)
    {
        $dateTime = new \DateTime();
        $expire = $dateTime->add(new \DateInterval("P30D"));
        $cookie = new Cookie('id', $user->getId(), $expire);
        $response->headers->setCookie($cookie);
        $cookie = new Cookie('accessToken', $user->getAccessToken(), $expire);
        $response->headers->setCookie($cookie);
    }
}

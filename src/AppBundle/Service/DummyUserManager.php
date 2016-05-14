<?php
namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Helper\StringGenerator;
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
     * @param Request $request
     * @return User|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getUser(Request $request)
    {
        $id = $request->cookies->get('id');
        $accessToken = $request->cookies->get('accessToken');
        $user = $this->em->find('AppBundle:User', intval($id));
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
        $token = StringGenerator::generateToken();
        $user->setAccessToken($token);

        $em = $this->em;
        $em->persist($user);
        $em->flush();

        return $user;
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

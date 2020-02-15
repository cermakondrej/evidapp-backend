<?php


namespace EvidApp\Security\Application\Request\Login;


use EvidApp\Security\Domain\Exception\AuthenticationException;

use Leos\Domain\User\Model\User;
use Leos\Domain\User\Repository\UserRepositoryInterface;

use Leos\Infrastructure\SecurityBundle\Security\Model\Auth;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class LoginHandler
 *
 * @package Leos\Application\UseCase\Security
 */
class LoginHandler
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTManager;


    public function __construct(
        AuthenticationUtils $authenticationUtils,
        UserRepositoryInterface $userRepository,
        EncoderFactoryInterface $encoderFactory,
        JWTTokenManagerInterface $JWTManager
    )
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->JWTManager = $JWTManager;
    }


    public function handle(LoginRequest $request): string
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByUsername($request->username());

        if (!$user) {

            throw new AuthenticationException();
        }

        $authUser = new Auth(
            (string) $user->uuid(),
            $user->auth()
        );

        $encoder = $this->encoderFactory->getEncoder($authUser);

        if (!$encoder->isPasswordValid($authUser->getPassword(), $request->plainPassword(), $authUser->getSalt())) {

            throw new AuthenticationException();
        }

        return $this->JWTManager->create($authUser);
    }
}
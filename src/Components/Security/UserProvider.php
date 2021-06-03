<?php

namespace App\Components\Security;

use App\Components\Api\AccessToken;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername(string $username)
    {
        throw new \RuntimeException('Invalid call');
    }
    
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        throw new \RuntimeException('Invalid call');
    }

    /**
     * @param AccessToken $user
     *
     * @return AccessToken
     */
    public function refreshUser(UserInterface $user)
    {
        if (time() >= $user->getExpire()->getTimestamp()) {
            throw new UserNotFoundException('AccessToken expired');
        }

        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     */
    public function supportsClass($class): bool
    {
        return AccessToken::class === $class;
    }
}

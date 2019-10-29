<?php

namespace App\Components\Security;

use App\Components\Api\AccessToken;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        throw new \RuntimeException('Invalid call');
    }

    /**
     * @param AccessToken $user
     * @return AccessToken
     */
    public function refreshUser(UserInterface $user)
    {
        if (time() >= $user->getExpire()->getTimestamp()) {
            throw new UsernameNotFoundException('AccessToken expired');
        }

        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === AccessToken::class;
    }
}
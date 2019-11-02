<?php

namespace App\Components\Security;

use App\Components\Api\AccessToken;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AccessToken) {
            throw new AccountExpiredException('Invalid token');
        }
    }

    /**
     * @param AccessToken $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (time() >= $user->getExpire()->getTimestamp()) {
            throw new AccountExpiredException('AccessToken expired');
        }
    }
}

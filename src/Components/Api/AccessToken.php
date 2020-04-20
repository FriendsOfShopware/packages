<?php

namespace App\Components\Api;

use App\Components\Encryption;
use App\Struct\Shop\Shop;
use Symfony\Component\Security\Core\User\UserInterface;

class AccessToken implements \JsonSerializable, UserInterface
{
    /**
     * @var \DateTime
     */
    protected $expire;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var Shop|null
     */
    protected $shop;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $userId;

    public function __toString()
    {
        $encryption = new Encryption();

        return $encryption->encrypt([
            'username' => $this->username,
            'password' => $this->password,
            'domain' => $this->shop->domain,
        ]);
    }

    public function getExpire(): \DateTime
    {
        return $this->expire;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public static function create(array $response): self
    {
        $me = new self();
        $me->userId = (int) $response['userId'];
        $me->token = $response['token'];
        $me->locale = $response['locale'] ?? 'de_DE'; // for sub-accounts the locale is missing in the response for some reason
        $me->username = $response['username'];
        $me->password = $response['password'];
        $me->expire = new \DateTime($response['expire']['date']);

        return $me;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(Shop $shop): void
    {
        $this->shop = $shop;
    }

    public function eraseCredentials()
    {
    }
}

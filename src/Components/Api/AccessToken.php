<?php

namespace App\Components\Api;

use App\Components\Encryption;
use App\Struct\CompanyMemberShip\Company;
use App\Struct\CompanyMemberShip\CompanyMemberShip;
use App\Struct\CompanyMemberShip\Permission;
use App\Struct\CompanyMemberShip\Role;
use App\Struct\Shop\Shop;
use Symfony\Component\Security\Core\User\UserInterface;

class AccessToken implements \JsonSerializable, UserInterface, \Stringable
{
    protected \DateTime $expire;

    protected string $username;

    protected string $password;

    protected ?Shop $shop = null;

    protected string $locale;

    protected string $token;

    protected int $userId;

    protected int $userAccountId;

    protected CompanyMemberShip $memberShip;

    public function __toString(): string
    {
        $encryption = new Encryption();

        if ($this->shop === null) {
            throw new \RuntimeException('Cannot serialize without shop');
        }

        return $encryption->encrypt([
            'username' => $this->username,
            'password' => $this->password,
            'domain' => $this->shop->domain,
            'userId' => $this->userId,
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

    public function setUserId(int $id): void
    {
        $this->userId = $id;
    }

    public function getUserAccountId(): int
    {
        return $this->userAccountId;
    }

    /**
     * @param array{'userId': string, 'userAccountId': int, 'token': string, 'locale': string, 'username': string, 'password': string, 'expire': array{'date': string}} $response
     */
    public static function create(array $response): static
    {
        $me = new static();
        $me->userId = (int) $response['userId'];
        $me->userAccountId = (int) $response['userAccountId'];
        $me->token = $response['token'];
        $me->locale = $response['locale'] ?? 'en_GB'; // for sub-accounts the locale is missing in the response for some reason
        $me->username = $response['username'];
        $me->password = $response['password'];
        $me->expire = new \DateTime($response['expire']['date']);

        // Remove this when shopwareId is completely removed from the system
        $memberShip = new CompanyMemberShip();
        $memberShip->company = new Company();
        $memberShip->company->name = $response['username'];
        $role = new Role();
        $role->permissions = [Permission::create(CompanyMemberShip::COMPANY_SHOPS_PERMISSION), Permission::create(CompanyMemberShip::PARTNER_SHOPS_PERMISSION), Permission::create(CompanyMemberShip::WILDCARD_SHOP_PERMISSION)];
        $memberShip->roles = [$role];
        $me->memberShip = $memberShip;

        return $me;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
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

    public function eraseCredentials(): void
    {
    }

    public function setMemberShip(CompanyMemberShip $memberShip): void
    {
        $this->memberShip = $memberShip;
    }

    public function getMemberShip(): CompanyMemberShip
    {
        return $this->memberShip;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 22/09/18
 * Time: 13:38
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation as ApiPlatform;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="user_unique_username",
 *              columns={"username"}
 *          )
 *      }
 * )
 * @UniqueEntity("username")
 * @ApiPlatform\ApiResource()
 */
class User implements UserInterface
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=500)
     */
    private $password;

    /**
     * @var bool
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var UserRole[]|ArrayCollection|Collection
     * @ORM\ManyToMany(targetEntity="UserRole", mappedBy="users", cascade={"persist", "remove"})
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $userRoles;

    /**
     * @var Privilege[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Privilege", mappedBy="user", cascade={"persist", "remove"})
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $privileges;

    public function __construct()
    {
        $this->isActive = true;
        $this->userRoles = new ArrayCollection();
        $this->privileges = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive(bool $isActive): User
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = [];
        foreach ($this->userRoles as $userRole) {
            $roles[] = $userRole->getLabel();
        }

        return $roles;
    }

    /**
     * @return UserRole[]|ArrayCollection|Collection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param UserRole[]|ArrayCollection|Collection $userRoles
     *
     * @return User
     */
    public function setUserRoles(array $userRoles = []): User
    {
        foreach ($userRoles as $userRole) {
            $this->addUserRole($userRole);
        }

        return $this;
    }

    /**
     * @param UserRole $userRole
     *
     * @return User
     */
    public function addUserRole(UserRole $userRole): User
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->addUser($this);
        }

        return $this;
    }

    /**
     * @param UserRole $userRole
     *
     * @return User
     */
    public function removeUserRole(UserRole $userRole): User
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Privilege[]|ArrayCollection|Collection
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * @param Privilege[]|ArrayCollection|Collection $privileges
     *
     * @return User
     */
    public function setPrivileges(array $privileges = []): User
    {
        foreach ($privileges as $privilege) {
            $this->addPrivilege($privilege);
        }

        return $this;
    }

    /**
     * @param Privilege $privilege
     *
     * @return User
     */
    public function addPrivilege(Privilege $privilege): User
    {
        if (!$this->privileges->contains($privilege)) {
            $this->privileges->add($privilege);
            $privilege->setUser($this);
        }

        return $this;
    }

    /**
     * @param Privilege $privilege
     *
     * @return User
     */
    public function removePrivilege(Privilege $privilege): User
    {
        if ($this->privileges->contains($privilege)) {
            $this->privileges->removeElement($privilege);
            $privilege->setUser(null);
        }

        return $this;
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
    }
}
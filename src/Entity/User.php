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
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     */
    private $username = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank()
     */
    private $password = '';

    /**
     * @var bool
     * @ORM\Column(name="is_active", type="boolean", options={"default": false})
     */
    private $isActive = false;

    /**
     * @var UserRole[]|ArrayCollection|Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\UserRole", mappedBy="users", cascade={"persist", "remove"})
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $userRoles;

    /**
     * @var Permission[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Permission", mappedBy="user", cascade={"persist", "remove"})
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $permissions;

    public function __construct()
    {
        $this->isActive = true;
        $this->userRoles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
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
     * @return Permission[]|ArrayCollection|Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param Permission[]|ArrayCollection|Collection $permissions
     *
     * @return User
     */
    public function setPermissions(array $permissions = []): User
    {
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }

        return $this;
    }

    /**
     * @param Permission $permission
     *
     * @return User
     */
    public function addPermission(Permission $permission): User
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->setUser($this);
        }

        return $this;
    }

    /**
     * @param Permission $permission
     *
     * @return User
     */
    public function removePersona(Permission $permission): User
    {
        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
            $permission->setUser(null);
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
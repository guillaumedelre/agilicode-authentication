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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="user__unique__username",
 *              columns={"username"}
 *          )
 *      }
 * )
 * @UniqueEntity("username")
 * @ApiPlatform\ApiResource(
 *     normalizationContext={"groups"={"user_get"}},
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "put"={
 *             "normalization_context"={"groups"={"user_put"}},
 *             "denormalization_context"={"groups"={"user_put"}}
 *         },
 *         "delete"={"method"="DELETE"}
 *     },
 *     collectionOperations={
 *         "get"={"method"="GET"},
 *         "post"={
 *             "normalization_context"={"groups"={"user_post"}},
 *             "denormalization_context"={"groups"={"user_post"}}
 *         }
 *     }
 * )
 * @ApiPlatform\ApiFilter(SearchFilter::class, properties={"username": "iexact"})
 */
class User implements UserInterface
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"user_get", "user_post", "user_put"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Groups({"user_get", "user_post", "user_put"})
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
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"user_get", "user_post", "user_put"})
     */
    private $enabled = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"user_get", "user_post", "user_put"})
     */
    private $service = false;

    /**
     * @var Privilege[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Privilege", mappedBy="user", cascade={"persist","remove"})
     * @Groups({"user_get", "user_post", "user_put"})
     * @ApiPlatform\ApiSubresource()
     */
    private $privileges;

    /**
     * @var Preference[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Preference", mappedBy="user", cascade={"persist","remove"})
     * @Groups({"user_get", "user_post", "user_put"})
     * @ApiPlatform\ApiSubresource()
     */
    private $preferences;

    public function __construct()
    {
        $this->privileges = new ArrayCollection();
        $this->preferences = new ArrayCollection();
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
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return User
     */
    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isService(): bool
    {
        return $this->service;
    }

    /**
     * @param bool $service
     */
    public function setService(bool $service): void
    {
        $this->service = $service;
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
        foreach ($this->privileges as $privilege) {
            foreach ($privilege->getRoles() as $role) {
                $roles[] = $role->getLabel();
            }
        }

        return array_unique($roles);
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
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
        $this->privileges = new ArrayCollection($privileges);

        return $this;
    }

    /**
     * @return Preference[]|ArrayCollection|Collection
     */
    public function getPreferences()
    {
        return $this->preferences;
    }

    /**
     * @param Preference[]|ArrayCollection|Collection $preferences
     *
     * @return User
     */
    public function setPreferences(array $preferences = []): User
    {
        $this->preferences = new ArrayCollection($preferences);

        return $this;
    }

}
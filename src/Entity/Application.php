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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Application
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="application__unique__label",
 *              columns={"label"}
 *          )
 *      }
 * )
 * @UniqueEntity("label")
 * @ApiPlatform\ApiResource(
 *     normalizationContext={"groups"={"application_get"}},
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "put"={
 *             "normalization_context"={"groups"={"application_put"}},
 *             "denormalization_context"={"groups"={"application_put"}}
 *         },
 *         "delete"={"method"="DELETE"}
 *     },
 *     collectionOperations={
 *         "get"={"method"="GET"},
 *         "post"={
 *             "normalization_context"={"groups"={"application_post"}},
 *             "denormalization_context"={"groups"={"application_post"}}
 *         }
 *     }
 * )
 */
class Application
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"application_get", "application_post", "application_put"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Groups({"application_get", "application_post", "application_put"})
     */
    private $label = '';

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"application_get", "application_post", "application_put"})
     */
    private $enabled = false;

    /**
     * @var Permission[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Permission", mappedBy="application")
     * @Groups({"application_get"})
     */
    private $permissions;

    /**
     * @var Role[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Role", mappedBy="application")
     * @Groups({"application_get"})
     */
    private $roles;

    /**
     * @var Privilege[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Privilege", mappedBy="application")
     * @Groups({"application_get"})
     */
    private $privileges;

    /**
     * @var Preference[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Preference", mappedBy="application")
     * @Groups({"application_get"})
     */
    private $preferences;

    /**
     * @var User[]|ArrayCollection|Collection
     * @Groups({"application_get"})
     */
    private $users;

    public function __construct()
    {
        $this->privileges = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->preferences = new ArrayCollection();
        $this->roles = new ArrayCollection();
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
     * @return Application
     */
    public function setId(int $id): Application
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
     * @return Application
     */
    public function setEnabled(bool $enabled): Application
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @var string $label
     * @return Application
     */
    public function setLabel(string $label): Application
    {
        $this->label = $label;

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
     * @return Role[]|ArrayCollection|Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return User[]|ArrayCollection|Collection
     * @Groups({"application_get"})
     */
    public function getUsers()
    {
        $users = [];
        foreach ($this->privileges as $privilege) {
            $users[] = $privilege->getUser();
        }

        return $users;
    }

    /**
     * @return Privilege[]|ArrayCollection|Collection
     * @Groups({"application_get"})
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * @return Preference[]|ArrayCollection|Collection
     * @Groups({"application_get"})
     */
    public function getPreferences()
    {
        return $this->preferences;
    }

}
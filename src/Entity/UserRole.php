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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserRole
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="user_role_unique_label",
 *              columns={"label"}
 *          )
 *      }
 * )
 * @UniqueEntity("label")
 * @ApiPlatform\ApiResource()
 */
class UserRole
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
    private $label = '';

    /**
     * @var User[]|ArrayCollection|Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="userRoles")
     * @ORM\JoinTable(
     *      name="roles_users",
     *      joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="user_role_id", referencedColumnName="id", unique=true)
     *      }
     * )
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    protected $users;

    /**
     * UserRole constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return UserRole
     */
    public function setId(int $id): UserRole
    {
        $this->id = $id;

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
     * @param string $label
     *
     * @return UserRole
     */
    public function setLabel(string $label): UserRole
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return User[]|ArrayCollection|Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User[]|ArrayCollection|Collection $users
     *
     * @return UserRole
     */
    public function setUsers(array $users = []): UserRole
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return UserRole
     */
    public function addUser(User $user): UserRole
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUserRole($this);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return UserRole
     */
    public function removeUser(User $user): UserRole
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUserRole($this);
        }

        return $this;
    }


}
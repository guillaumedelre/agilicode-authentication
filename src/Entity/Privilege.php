<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 08/10/18
 * Time: 13:00
 */

namespace App\Entity;


use ApiPlatform\Core\Annotation as ApiPlatform;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Privilege
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="privilege__unique__user_application",
 *              columns={"user_id", "application_id"}
 *          )
 *      }
 * )
 * @UniqueEntity({"user", "application"})
 * @ApiPlatform\ApiResource(
 *     normalizationContext={"groups"={"privilege_get"}},
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "put"={
 *             "normalization_context"={"groups"={"privilege_put"}},
 *             "denormalization_context"={"groups"={"privilege_put"}}
 *         },
 *         "delete"={"method"="DELETE"}
 *     },
 *     collectionOperations={
 *         "get"={"method"="GET"},
 *         "post"={
 *             "normalization_context"={"groups"={"privilege_post"}},
 *             "denormalization_context"={"groups"={"privilege_post"}}
 *         }
 *     }
 * )
 */
class Privilege
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"privilege_get", "privilege_post", "privilege_put"})
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="privileges")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\Valid()
     * @Groups({"privilege_get", "privilege_post", "privilege_put"})
     */
    private $user;

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="privileges")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\Valid()
     * @Groups({"privilege_get", "privilege_post", "privilege_put"})
     */
    private $application;

    /**
     * @var Role[]|ArrayCollection|Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     * @Assert\Count(min=1)
     * @Groups({"privilege_get", "privilege_post", "privilege_put"})
     */
    private $roles;

    public function __construct()
    {
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
     * @return Privilege
     */
    public function setId(int $id): Privilege
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Privilege
     */
    public function setUser(User $user): Privilege
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Application
     */
    public function getApplication(): Application
    {
        return $this->application;
    }

    /**
     * @param Application $application
     *
     * @return Privilege
     */
    public function setApplication(Application $application): Privilege
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return Role[]|ArrayCollection|Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Role[]|ArrayCollection|Collection
     *
     * @return Privilege
     */
    public function setRoles($roles): Privilege
    {
        $this->roles = new ArrayCollection($roles);

        return $this;
    }

}
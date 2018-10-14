<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 15/10/18
 * Time: 09:38
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation as ApiPlatform;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Preference
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="preference__unique__user_application",
 *              columns={"user_id", "application_id"}
 *          )
 *      }
 * )
 * @UniqueEntity({"user", "application"})
 * @ApiPlatform\ApiResource(
 *     normalizationContext={"groups"={"preference_get"}},
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "put"={
 *             "normalization_context"={"groups"={"preference_put"}},
 *             "denormalization_context"={"groups"={"preference_put"}}
 *         },
 *         "delete"={"method"="DELETE"}
 *     },
 *     collectionOperations={
 *         "get"={"method"="GET"},
 *         "post"={
 *             "normalization_context"={"groups"={"preference_post"}},
 *             "denormalization_context"={"groups"={"preference_post"}}
 *         }
 *     }
 * )
 */
class Preference
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"preference_get", "preference_post", "preference_put"})
     */
    private $id;

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity="App\Entity\Application")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotNull()
     * @Groups({"preference_get", "preference_post", "preference_put"})
     */
    private $application;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="preferences")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotNull()
     * @Groups({"preference_get", "preference_post", "preference_put"})
     */
    private $user;

    /**
     * @var array
     * @ORM\Column(type="json_document")
     * @Assert\NotNull()
     * @Groups({"preference_get", "preference_post", "preference_put"})
     */
    private $data = [];

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
     * @return Preference
     */
    public function setId(int $id): Preference
    {
        $this->id = $id;
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
     * @return Preference
     */
    public function setApplication(Application $application): Preference
    {
        $this->application = $application;
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
     * @return Preference
     */
    public function setUser(User $user): Preference
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return Preference
     */
    public function setData(array $data = []): Preference
    {
        $this->data = $data;
        return $this;
    }

}
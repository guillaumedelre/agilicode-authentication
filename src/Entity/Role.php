<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 22/09/18
 * Time: 13:38
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation as ApiPlatform;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Role
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="role__unique__label__application",
 *              columns={"label", "application_id"}
 *          )
 *      }
 * )
 * @UniqueEntity({"label", "application"})
 * @ApiPlatform\ApiResource(
 *     normalizationContext={"groups"={"role_get"}},
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "put"={
 *             "normalization_context"={"groups"={"role_put"}},
 *             "denormalization_context"={"groups"={"role_put"}}
 *         },
 *         "delete"={"method"="DELETE"}
 *     },
 *     collectionOperations={
 *         "get"={"method"="GET"},
 *         "post"={
 *             "normalization_context"={"groups"={"role_post"}},
 *             "denormalization_context"={"groups"={"role_post"}}
 *         }
 *     }
 * )
 */
class Role
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"role_get", "role_post", "role_put"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({"role_get", "role_post", "role_put"})
     */
    private $label = '';

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="roles")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank()
     * @Assert\Valid()
     * @Groups({"role_get", "role_post", "role_put"})
     */
    private $application;

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
     * @return Role
     */
    public function setId(int $id): Role
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
     * @return Role
     */
    public function setLabel(string $label): Role
    {
        $this->label = $label;
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
     * @param Application
     *
     * @return Role
     */
    public function setApplication($application): Role
    {
        $this->application = $application;

        return $this;
    }

}
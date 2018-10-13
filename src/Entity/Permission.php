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
 * Class Permission
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="permission__unique__label_application",
 *              columns={"label", "application_id"}
 *          )
 *      }
 * )
 * @UniqueEntity({"label", "application"})
 * @ApiPlatform\ApiResource(
 *     normalizationContext={"groups"={"permission_get"}},
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "put"={
 *             "normalization_context"={"groups"={"permission_put"}},
 *             "denormalization_context"={"groups"={"permission_put"}}
 *         },
 *         "delete"={"method"="DELETE"}
 *     },
 *     collectionOperations={
 *         "get"={"method"="GET"},
 *         "post"={
 *             "normalization_context"={"groups"={"permission_post"}},
 *             "denormalization_context"={"groups"={"permission_post"}}
 *         }
 *     }
 * )
 */
class Permission
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"permission_get", "permission_post", "permission_put"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({"permission_get", "permission_post", "permission_put"})
     */
    private $label = '';

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="permissions")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\Valid()
     * @Groups({"permission_get", "permission_post", "permission_put"})
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
     * @return Permission
     */
    public function setId(int $id): Permission
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
     * @var string $label
     * @return Permission
     */
    public function setLabel(string $label): Permission
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
     * @return Permission
     */
    public function setApplication($application): Permission
    {
        $this->application = $application;

        return $this;
    }
}
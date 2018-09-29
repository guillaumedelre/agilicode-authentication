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
 * Class Application
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="application_unique_label",
 *              columns={"label"}
 *          )
 *      }
 * )
 * @UniqueEntity("label")
 * @ApiPlatform\ApiResource()
 */
class Application
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
     * @var bool
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var Permission[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Permission", mappedBy="application", cascade={"persist", "remove"})
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $permissions;

    public function __construct()
    {
        $this->isActive = true;
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
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return Application
     */
    public function setIsActive(bool $isActive): Application
    {
        $this->isActive = $isActive;

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
    public function getPersonas()
    {
        return $this->permission;
    }

    /**
     * @param Permission[]|ArrayCollection|Collection $permissions
     *
     * @return Application
     */
    public function setPermissions(array $permissions = []): Application
    {
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }

        return $this;
    }

    /**
     * @param Permission $permission
     *
     * @return Application
     */
    public function addPermission(Permission $permission): Application
    {
        if (!$this->permission->contains($permission)) {
            $this->permission->add($permission);
            $permission->setApplication($this);
        }

        return $this;
    }

    /**
     * @param Permission $permission
     *
     * @return Application
     */
    public function removePersona(Permission $permission): Application
    {
        if ($this->permission->contains($permission)) {
            $this->permission->removeElement($permission);
            $permission->setApplication(null);
        }

        return $this;
    }

}
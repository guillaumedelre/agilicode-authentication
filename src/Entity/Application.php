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
     */
    private $label;

    /**
     * @var bool
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var Privilege[]|ArrayCollection|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Privilege", mappedBy="user", cascade={"persist", "remove"})
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $privileges;

    public function __construct()
    {
        $this->isActive = true;
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
     * @return Privilege[]|ArrayCollection|Collection
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * @param Privilege[]|ArrayCollection|Collection $privileges
     *
     * @return Application
     */
    public function setPrivileges(array $privileges = []): Application
    {
        foreach ($privileges as $privilege) {
            $this->addPrivilege($privilege);
        }

        return $this;
    }

    /**
     * @param Privilege $privilege
     *
     * @return Application
     */
    public function addPrivilege(Privilege $privilege): Application
    {
        if (!$this->privileges->contains($privilege)) {
            $this->privileges->add($privilege);
            $privilege->setApplication($this);
        }

        return $this;
    }

    /**
     * @param Privilege $privilege
     *
     * @return Application
     */
    public function removePrivilege(Privilege $privilege): Application
    {
        if ($this->privileges->contains($privilege)) {
            $this->privileges->removeElement($privilege);
            $privilege->setApplication(null);
        }

        return $this;
    }

}
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

/**
 * Class Permission
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="permission_unique_application_user",
 *              columns={"application_id", "user_id"}
 *          )
 *      }
 * )
 * @UniqueEntity({"application", "user"})
 * @ApiPlatform\ApiResource()
 */
class Permission
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="permissions")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $application;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="permissions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $user;

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
     * @return Application
     */
    public function getApplication(): Application
    {
        return $this->application;
    }

    /**
     * @param Application $application
     *
     * @return Permission
     */
    public function setApplication(Application $application): Permission
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
     * @return Permission
     */
    public function setUser(User $user): Permission
    {
        $this->user = $user;

        return $this;
    }

}
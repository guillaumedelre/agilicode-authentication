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
 * Class Privilege
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="privilege_unique_application_user",
 *              columns={"application_id", "user_id"}
 *          )
 *      }
 * )
 * @UniqueEntity({"application", "user"})
 * @ApiPlatform\ApiResource()
 */
class Privilege
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="privileges")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @ApiPlatform\ApiSubresource(maxDepth=1)
     */
    private $application;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="privileges")
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
     * @return Privilege
     */
    public function setId(int $id): Privilege
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
     * @return Privilege
     */
    public function setApplication(Application $application): Privilege
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
     * @return Privilege
     */
    public function setUser(User $user): Privilege
    {
        $this->user = $user;

        return $this;
    }

}
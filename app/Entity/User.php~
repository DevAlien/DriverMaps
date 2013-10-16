<?php

namespace app\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aliegon\Auth\UserInterface;

/**
 * Devalien\WebBundle\Entity\User
 *
 * @Table(name="user")
 * @Entity()
 *
 */
class User implements UserInterface
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id()
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $username
     *
     * @Column(name="username", type="string", length=60, unique=true, nullable=true)
     */
    protected $username;

    /**
     * @var string $email
     *
     * @Column(name="email", type="string", length=60, unique=true)
     */
    protected $email;

    /**
     * @var string $password
     *
     * @Column(name="password", type="string", length=150)
     */
    protected $password;

    /**
     * @var string $salt
     *
     * @Column(name="salt", type="string", length=40)
     */
    protected $salt;

    /**
     * @var \DateTime $createdOn
     *
     * @Column(name="created_on", type="datetime")
     */
    protected $createdOn;

    /**
     * @var \DateTime $updatedOn
     *
     * @Column(name="updated_on", type="datetime")
     */
    protected $updatedOn;

    /**
     * @var \DateTime $deletedOn
     *
     * @Column(name="deleted_on", type="datetime")
     */
    protected $deletedOn;

    /**
     * @var \DateTime $activatedOn
     *
     * @Column(name="activated_on", type="datetime")
     */
    protected $activatedOn;

    /**
     * @OneToOne(targetEntity="UserData", cascade={"persist", "remove"})
     * @JoinColumn(name="user_data_id", referencedColumnName="id")
     */
    protected $userData;

    public function __construct()
    {
        $this->createdOn  = new \DateTime();
        $this->updatedOn  = new \DateTime();
        $this->deletedOn  = new \DateTime();
        $this->deletedOn->setDate(0,0,0);
        $this->activatedOn  = new \DateTime();
        $this->activatedOn->setDate(0,0,0);
        $this->salt = "a not very good salt";
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = md5($this->getSalt() . $password . $this->getSalt());
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function isActive()
    {
        return true;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return User
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    
        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime 
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     * @return User
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    
        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime 
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set deletedOn
     *
     * @param \DateTime $deletedOn
     * @return User
     */
    public function setDeletedOn($deletedOn)
    {
        $this->deletedOn = $deletedOn;
    
        return $this;
    }

    /**
     * Get deletedOn
     *
     * @return \DateTime 
     */
    public function getDeletedOn()
    {
        return $this->deletedOn;
    }

    /**
     * Set activatedOn
     *
     * @param \DateTime $activatedOn
     * @return User
     */
    public function setActivatedOn($activatedOn)
    {
        $this->activatedOn = $activatedOn;
    
        return $this;
    }

    /**
     * Get activatedOn
     *
     * @return \DateTime 
     */
    public function getActivatedOn()
    {
        return $this->activatedOn;
    }

    public function getPermissions()
    {
        return array();
    }

    /**
     * Set userData
     *
     * @param \app\Entity\UserData $userData
     * @return User
     */
    public function setUserData(\app\Entity\UserData $userData = null)
    {
        $this->userData = $userData;
    
        return $this;
    }

    /**
     * Get userData
     *
     * @return \app\Entity\UserData 
     */
    public function getUserData()
    {
        return $this->userData;
    }
}
<?php

namespace app\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devalien\WebBundle\Entity\UserData
 *
 * @Table(name="user_data")
 * @Entity()
 *
 */
class UserData
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
     * @var string $name
     *
     * @Column(name="name", type="string", length=60)
     */
    protected $name;

    /**
     * @var string $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string $topPicture
     *
     * @Column(name="top_picture", type="string", length=255, nullable=true)
     */
    protected $topPicture;

    /**
     * @var string $profilePicture
     *
     * @Column(name="profile_picture", type="string", length=255, nullable=true)
     */
    protected $profilePicture;

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

    public function __construct()
    {
        $this->createdOn  = new \DateTime();
        $this->updatedOn  = new \DateTime();
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
     * Set name
     *
     * @param string $name
     * @return UserData
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return UserData
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set topPicture
     *
     * @param string $topPicture
     * @return UserData
     */
    public function setTopPicture($topPicture)
    {
        $this->topPicture = $topPicture;
    
        return $this;
    }

    /**
     * Get topPicture
     *
     * @return string 
     */
    public function getTopPicture()
    {
        return $this->topPicture;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return UserData
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
     * @return UserData
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
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return UserData
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    
        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string 
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }
}
<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Recite\DataBundle\Entity\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="user_role")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="user")
     */
    private $courses;

    public function __construct()
    {
        $this->salt = md5(uniqid(null, true));
        $this->roles = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $roles = [];

        foreach($this->roles as $role){
            $roles[] = $role->getRole();
        }

        return $roles;
    }

    public function getTopRole(){
        $roles = $this->getRoles();

        if(in_array('ROLE_ADMIN', $roles)){
            return 'admin';
        }

        if(in_array('ROLE_USER', $roles)){
            return 'user';
        }

        return 'anonymous';
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

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
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
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
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->salt,
            $this->password,
            $this->username
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->salt,
            $this->password,
            $this->username
        ) = unserialize($serialized);
    }

    /**
     * Add roles
     *
     * @param \Recite\DataBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Recite\DataBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Recite\DataBundle\Entity\Role $roles
     */
    public function removeRole(\Recite\DataBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    public function getCourseByBook($book){
        $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('book', $book))
                ->setMaxResults(1);

        return $this->courses->matching($criteria)->first();
    }

    public function getOpenedCourses(){
        $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('status', Course::STATUS_OPEN));

        return $this->courses->matching($criteria);
    }

    public function getClosedCourses(){
        $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('status', Course::STATUS_CLOSE));

        return $this->courses->matching($criteria);
    }

    /**
     * Add courses
     *
     * @param \Recite\DataBundle\Entity\Course $courses
     * @return User
     */
    public function addCourse(\Recite\DataBundle\Entity\Course $courses)
    {
        $this->courses[] = $courses;
    
        return $this;
    }

    /**
     * Remove courses
     *
     * @param \Recite\DataBundle\Entity\Course $courses
     */
    public function removeCourse(\Recite\DataBundle\Entity\Course $courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourses()
    {
        return $this->courses;
    }
}
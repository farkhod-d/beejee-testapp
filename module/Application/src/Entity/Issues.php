<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issues
 *
 * @ORM\Table(name="issues", indexes={@ORM\Index(name="IDX_issues_user_email", columns={"user_email"}), @ORM\Index(name="IDX_issues_status", columns={"status"}), @ORM\Index(name="IDX_issues_user_name", columns={"user_name"}), @ORM\Index(name="IDX_issues_created_at", columns={"created_at"})})
 * @ORM\Entity(repositoryClass="\Application\Repository\IssueRepository")
 */
class Issues
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=150, nullable=false, options={"comment"="Имя пользователя"})
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="string", length=255, nullable=false)
     */
    private $userEmail;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false, options={"comment"="Статус задачи"})
     */
    private $status = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"comment"="Дата создание"})
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", length=65535, nullable=false)
     */
    private $note;

    /**
     * Issues constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Issues
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return Issues
     */
    public function setUserName(string $userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     * @return Issues
     */
    public function setUserEmail(string $userEmail)
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return Issues
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Issues
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     * @return Issues
     */
    public function setUpdatedAt(?\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return Issues
     */
    public function setNote(string $note)
    {
        $this->note = $note;
        return $this;
    }



}

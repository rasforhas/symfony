<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    public const PUBLISHED = 1;
    public const DRAFT = 0;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameCreator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surnameCreator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $participation;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $doi;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $numOfPoints;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $conference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Creator")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

//    /**
//     * @ORM\ManyToOne(targetEntity=User::class)
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $post;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_published;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getNameCreator(): ?string
    {
        return $this->nameCreator;
    }

    public function setNameCreator(?string $nameCreator): self
    {
        $this->nameCreator = $nameCreator;

        return $this;
    }

    public function getSurnameCreator(): ?string
    {
        return $this->surnameCreator;
    }

    public function setSurnameCreator(?string $surnameCreator): self
    {
        $this->surnameCreator = $surnameCreator;

        return $this;
    }

    public function getParticipation(): ?string
    {
        return $this->participation;
    }

    public function setParticipation(string $participation): self
    {
        $this->participation = $participation;

        return $this;
    }

    public function getDoi(): ?string
    {
        return $this->doi;
    }

    public function setDoi(?string $doi): self
    {
        $this->doi = $doi;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAtValue()
    {
        $this->create_at = new \DateTime();
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getNumOfPoints(): ?int
    {
        return $this->numOfPoints;
    }

    public function setNumOfPoints(int $numOfPoints): self
    {
        $this->numOfPoints = $numOfPoints;

        return $this;
    }

    public function getConference(): ?string
    {
        return $this->conference;
    }

    public function setConference(string $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    public function getCreator(): ?Creator
    {
        return $this->creator;
    }

    public function setCreator(?Creator $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getPost(): ?User
    {
        return $this->post;
    }

    public function setPost(?User $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }

    public function setUpdateAtValue()
    {
        $this->update_at = new \DateTime();
    }

    public function setUpdateAt(?\DateTimeInterface $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->is_published;
    }

    public function setIsPublished()
    {
        $this->is_published = self::PUBLISHED;
    }

    public function setIsDraft()
    {
        $this->is_published = self::DRAFT;
    }
}

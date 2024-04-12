<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use ORM\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["searchable"])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(["searchable"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["searchable"])]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userComment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $bookComment = null;

    #[Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: Types::DATE_IMMUTABLE)]
    #[Groups(["searchable"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Timestampable(on: 'update')]
    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE)]
    #[Groups(["searchable"])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?int $rating = null;

    public function __toString()
    {
        return $this->getTitle();
    }

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUserComment(): ?User
    {
        return $this->userComment;
    }

    public function setUserComment(?User $userComment): self
    {
        $this->userComment = $userComment;

        return $this;
    }

    public function getBookComment(): ?Book
    {
        return $this->bookComment;
    }

    public function setBookComment(?Book $bookComment): self
    {
        $this->bookComment = $bookComment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}

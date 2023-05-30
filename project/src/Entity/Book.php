<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation\Slug;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["searchable"])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(["searchable"])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(["searchable"])]
    private ?string $introduction = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["searchable"])]
    private ?string $description = null;

    #[Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: Types::DATE_IMMUTABLE)]
    #[Groups(["searchable"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Timestampable(on: 'update')]
    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE)]
    #[Groups(["searchable"])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Timestampable(on: 'change')] //, field:["title", "body"]
    #[ORM\Column(name: 'published_at', type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(["searchable"])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[Slug(fields: ['title'])]
    #[ORM\Column(length: 255, unique: true)]
    #[Groups(["searchable"])]
    private ?string $slug = null;

    #[ORM\Column]
    #[Groups(["searchable"])]
    private ?float $price = null;

    #[ORM\Column(length: 20)]
    #[Groups(["searchable"])]
    private ?string $langue = null;

    #[ORM\Column]
    #[Groups(["searchable"])]
    private ?int $nbPages = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(["searchable"])]
    private ?string $dimension = null;

    #[Assert\Isbn(
        type: Assert\Isbn::ISBN_10,
        message: 'Cette valeur n\'est pas valide.',
    )]
    #[ORM\Column(length: 50)]
    #[Groups(["searchable"])]
    private ?string $isbn = null;

    #[ORM\Column(length: 50)]
    #[Groups(["searchable"])]
    private ?string $editor = null;

    #[ORM\Column]
    #[Groups(["searchable"])]
    private ?bool $isInStock = null;

    #[ORM\OneToMany(mappedBy: 'bookComment', targetEntity: Comment::class, orphanRemoval: true)]
    #[Groups(["searchable"])]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'book')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'book')]
    private Collection $authors;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Image::class, orphanRemoval: true)]
    #[Groups(["searchable"])]
    private Collection $images;

    #[ORM\Column]
    private ?int $rating = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getNbPages(): ?int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): self
    {
        $this->nbPages = $nbPages;

        return $this;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(?string $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(string $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function isIsInStock(): ?bool
    {
        return $this->isInStock;
    }

    public function setIsInStock(bool $isInStock): self
    {
        $this->isInStock = $isInStock;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setBookComment($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBookComment() === $this) {
                $comment->setBookComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addBook($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setBook($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBook() === $this) {
                $image->setBook(null);
            }
        }

        return $this;
    }

    public function computeSlug(SluggerInterface $slugger)
    {
       if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }
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

    public function getAvgRating()
    {
        $sum = array_reduce($this->comments->toArray(), function($total, $comment){
            return $total + $comment->getRating();
        }, 0);

        if(count($this->comments) > 0) return $sum / count($this->comments);
        return 0;
    }
}

<?php

namespace App\Entity;

use App\Enum\Status;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class BlogArticle
{

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id; // Added ID field as primary key

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Author ID is required.")]
    private int $authorId;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: "Title is required.")]
    #[Assert\Length(max: 100, maxMessage: "Title cannot be longer than 100 characters.")]
    private $title;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "Publication date is required.")]
    #[Assert\Type("\DateTimeInterface")]
    private $publicationDate;

    #[ORM\Column(type: 'datetime')]
    private $creationDate;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Content cannot be blank.")]
    private $content;

    #[ORM\Column(type: 'json')]
    private $keywords = [];

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Slug is required.")]
    private $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $coverPictureRef;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    #[Assert\NotBlank(message: "Status is required.")]
    private Status $status;


    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): self
    {
        $this->authorId = $authorId;
        return $this;
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

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function setKeywords(array $keywords): self
    {
        $this->keywords = $keywords;
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

    public function getCoverPictureRef(): ?string
    {
        return $this->coverPictureRef;
    }

    public function setCoverPictureRef(?string $coverPictureRef): self
    {
        $this->coverPictureRef = $coverPictureRef;
        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
    }



}

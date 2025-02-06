<?php

namespace App\Documents;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Symfony\Component\Serializer\Annotation\Groups;

#[ODM\Document(collection: 'blog_post')]
class BlogPost
{
    #[ODM\Id]
    #[Groups(['default'])]
    private ?string $id = null;

    #[ODM\Field(type: "string")]
    #[Groups(['default'])]
    private string $title;

    #[ODM\Field(type: "string")]
    #[Groups(['default'])]
    private string $body;

    #[ReferenceMany(storeAs: "id", targetDocument: Comments::class)]
    #[Groups(['default'])]
    private Collection $comments;

    #[ODM\EmbedMany(targetDocument: Tags::class)]
    #[Groups(['default'])]
    private Collection $tags;

    #[ODM\Field(type: "date_immutable")]
    #[Groups(['default'])]
    private DateTimeImmutable $createdAt;

    public function __construct(string $title = '', string $body = '')
    {
        $this->title = $title;
        $this->body = $body;
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): void
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}

<?php

namespace App\Documents;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ODM\Document(collection: 'comments')]
class Comments
{
    #[ODM\Id]
    #[Groups(['default'])]
    public ?string $id = null;

    #[ODM\Field]
    #[Groups(['default'])]
    public string $text = '';

    #[ODM\Field]
    #[Groups(['default'])]
    public DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
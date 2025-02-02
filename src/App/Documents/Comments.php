<?php

namespace App\Documents;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'comments')]
class Comments
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field]
    public string $text = '';

    #[ODM\Field]
    public DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
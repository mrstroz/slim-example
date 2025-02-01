<?php

namespace App\Documents;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class BlogPost
{
    public function __construct(
        #[ODM\Id]
        public ?string $id = null,

        #[ODM\Field]
        public string $title = '',

        #[ODM\Field]
        public string $body = '',

        #[ODM\Field]
        public DateTimeImmutable $createdAt = new DateTimeImmutable(),
    ) {
    }
}
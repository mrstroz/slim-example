<?php

namespace App\Documents;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\EmbeddedDocument]
class Tags
{
    #[ODM\Field]
    public string $tag = '';
}
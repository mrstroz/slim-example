<?php

namespace App\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ODM\EmbeddedDocument]
class Tags
{
    #[ODM\Field]
    #[Groups(['default'])]
    public string $tag = '';
}
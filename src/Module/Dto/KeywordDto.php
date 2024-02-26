<?php

declare(strict_types=1);

namespace App\Module\Dto;

use App\Entity\Link;
use Symfony\Component\Validator\Constraints as Assert;

class KeywordDto
{
    #[Assert\NotBlank(message: 'keyword is required.', groups: ['required'])]
    public string $keyword;

    #[Assert\NotBlank(message: 'link is required.', groups: ['required'])]
    public Link $link;
}

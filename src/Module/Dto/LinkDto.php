<?php

declare(strict_types=1);

namespace App\Module\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LinkDto
{
    #[Assert\NotBlank(message: 'url is required.', groups: ['required'])]
    #[Assert\Url(groups: ['required'])]
    public string $url;
}

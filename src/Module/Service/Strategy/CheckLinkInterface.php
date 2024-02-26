<?php

namespace App\Module\Service\Strategy;

use App\Entity\Link;
use App\Entity\Result;

interface CheckLinkInterface
{
    public function check(Link $link): Result;
}

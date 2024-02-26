<?php

namespace App\Entity;

use App\Module\Repository\KeywordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: KeywordRepository::class)]
class Keyword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['keyword:list', 'keyword:create'])]
    private ?int $id = null;

    #[Groups(['keyword:list', 'keyword:item', 'keyword:create'])]
    #[ORM\Column(length: 255)]
    private ?string $keyword = null;

    #[Groups(['keyword:list', 'keyword:item', 'keyword:create'])]
    #[ORM\ManyToOne(inversedBy: 'keywords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Link $link = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): static
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function setLink(?Link $link): static
    {
        $this->link = $link;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Module\Repository\LinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
#[ORM\UniqueConstraint(name: "unique_url", columns: ["url"])]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['link:list', 'link:create', 'keyword:list', 'keyword:item', 'result:list', 'result:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups([
        'link:list',
        'link:item',
        'link:create',
        'keyword:list',
        'keyword:item',
        'keyword:create',
        'result:list',
        'result:item'
    ])]
    #[Assert\NotBlank(message: 'url is required.', groups: ['required'])]
    #[Assert\Url(groups: ['required'])]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['link:list', 'link:item'])]
    private ?\DateTimeImmutable $lastCheckedAt = null;

    /**
     * @var Collection<int, Keyword>
     */
    #[ORM\OneToMany(targetEntity: Keyword::class, mappedBy: 'link')]
    private Collection $keywords;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getLastCheckedAt(): ?\DateTimeImmutable
    {
        return $this->lastCheckedAt;
    }

    public function setLastCheckedAt(?\DateTimeImmutable $lastCheckedAt): static
    {
        $this->lastCheckedAt = $lastCheckedAt;

        return $this;
    }

    /**
     * @return Collection<int, Keyword>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
            $keyword->setLink($this);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): static
    {
        if ($this->keywords->removeElement($keyword)) {
            // set the owning side to null (unless already changed)
            if ($keyword->getLink() === $this) {
                $keyword->setLink(null);
            }
        }

        return $this;
    }
}

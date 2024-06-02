<?php


namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\IssueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IssueRepository::class)]
#[ApiResource(
    operations: [
        new Post(normalizationContext: [
            'groups' => ['issue:read']
        ],
            denormalizationContext: [
                'groups' => ['issue:write']
            ]),
        new GetCollection(normalizationContext: [
            'groups' => ['issue:read']
        ],
            denormalizationContext: [
                'groups' => ['issue:write']
            ]),
    ],
)]
#[ApiResource(
    uriTemplate: '/issues/{id}/update_status',
    operations: [new Post()],
)]
#[ORM\Table(name: '`issue`')]
class Issue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['issue:read'])]
    private  $id;

    #[Assert\NotBlank(groups: ['issue:write'])]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['issue:write', 'issue:read'])]
    private string $title;

    #[Assert\NotBlank(groups: ['issue:write'])]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['issue:write', 'issue:read'])]
    private string $description;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['issue:read', 'issue:put'])]
    private bool $isResolved = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsResolved(): bool
    {
        return $this->isResolved;
    }

    public function setIsResolved(bool $isResolved): self
    {
        $this->isResolved = $isResolved;

        return $this;
    }
}

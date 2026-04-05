<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Exception\Event\EventNotFoundException;
use App\Repository\EventRepository;
use App\State\Provider\EventItemProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(
            exceptionToStatus: [EventNotFoundException::class => 404],
            provider: EventItemProvider::class
        ),
        new GetCollection(),
    ]
)]
#[ApiFilter(DateFilter::class, properties: ['dateAvailable'])]
class Event
{
    use DateFieldsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['event:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['event:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 1024, nullable: true)]
    #[Groups(['event:read'])]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual('today')]
    #[Groups(['event:read'])]
    private ?\DateTime $dateEvent = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?\DateTime $dateAvailable = null;

    /**
     * @var Collection<int, EventCapacity>
     */
    #[ORM\OneToMany(targetEntity: EventCapacity::class, mappedBy: 'event', orphanRemoval: true)]
    #[Groups(['event:read'])]
    private Collection $locationCapacities;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Groups(['event:read'])]
    private ?Location $location = null;

    public function __construct()
    {
        $this->locationCapacities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDateEvent(): ?\DateTime
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTime $dateEvent): static
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getDateAvailable(): ?\DateTime
    {
        return $this->dateAvailable;
    }

    public function setDateAvailable(\DateTime $dateAvailable): static
    {
        $this->dateAvailable = $dateAvailable;

        return $this;
    }

    /**
     * @return Collection<int, EventCapacity>
     */
    public function getLocationCapacities(): Collection
    {
        return $this->locationCapacities;
    }

    public function addLocationCapacity(EventCapacity $locationCapacity): static
    {
        if (!$this->locationCapacities->contains($locationCapacity)) {
            $this->locationCapacities->add($locationCapacity);
            $locationCapacity->setEvent($this);
        }

        return $this;
    }

    public function removeLocationCapacity(EventCapacity $locationCapacity): static
    {
        if ($this->locationCapacities->removeElement($locationCapacity)) {
            // set the owning side to null (unless already changed)
            if ($locationCapacity->getEvent() === $this) {
                $locationCapacity->setEvent(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getTotalTickets(): int
    {
        return $this->locationCapacities->reduce(
            static fn (int $total, EventCapacity $capacity) => $total + $capacity->getCapacity(),
            0
        );
    }
}

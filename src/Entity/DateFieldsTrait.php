<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait DateFieldsTrait
{
    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateModified = null;

    public function getDateCreated(): ?\DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeImmutable $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    #[ORM\PrePersist]
    public function setDateCreatedValue(): void
    {
        $this->dateCreated = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setDateModifiedValue(): void
    {
        $this->dateModified = new \DateTime();
    }

    public function getDateModified(): ?\DateTime
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTime $dateModified): static
    {
        $this->dateModified = $dateModified;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 */
class Outing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $limitDateInscription;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotBlank(message="Fournissez le nombre de place.")
     * @Assert\Length(
     *     min=5,
     *     minMessage="Minimum 2 places",
     *     )
     */

    private $maxNbPart;

    /**
     * @ORM\Column(type="text")
     */
    private $outingReport;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;


    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="organizedOutings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organizerUser;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="outings")
     */
    private $registeredUsers;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    public function __construct()
    {
        $this->registeredUsers = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateTimeStart(): ?DateTime
    {
        return $this->dateTimeStart;
    }

    public function setDateTimeStart(DateTime $dateTimeStart): self
    {
        $this->dateTimeStart = $dateTimeStart;

        return $this;
    }

    public function getLimitDateInscription(): ?DateTime
    {
        return $this->limitDateInscription;
    }

    public function setLimitDateInscription(DateTime $limitDateInscription): self
    {
        $this->limitDateInscription = $limitDateInscription;

        return $this;
    }

    public function getMaxNbPart(): ?int
    {
        return $this->maxNbPart;
    }

    public function setMaxNbPart(int $maxNbPart): self
    {
        $this->maxNbPart = $maxNbPart;

        return $this;
    }

    public function getOutingReport(): ?string
    {
        return $this->outingReport;
    }

    public function setOutingReport(string $outingReport): self
    {
        $this->outingReport = $outingReport;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }


    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getOrganizerUser(): ?User
    {
        return $this->organizerUser;
    }

    public function setOrganizerUser(?User $organizerUser): self
    {
        $this->organizerUser = $organizerUser;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRegisteredUsers(): Collection
    {
        return $this->registeredUsers;
    }

    public function addRegisteredUser(User $registeredUser): self
    {
        if (!$this->registeredUsers->contains($registeredUser)) {
            $this->registeredUsers[] = $registeredUser;
        }

        return $this;
    }

    public function removeRegisteredUser(User $registeredUser): self
    {
        $this->registeredUsers->removeElement($registeredUser);

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }


}

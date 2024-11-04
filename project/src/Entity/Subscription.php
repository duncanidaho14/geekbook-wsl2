<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = 'free';

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(mappedBy: 'subscription', targetEntity: User::class)]
    private Collection $subscriber;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column(nullable: false)]
    private ?bool $isSubscriber = false;

    #[ORM\Column(length: 255)]
    private ?string $stripeId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $currentPeriodStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $currentPeriodEnd = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions', cascade:["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plan $plan = null;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(mappedBy: 'subscription', targetEntity: Invoice::class, cascade:["persist"])]
    private Collection $invoices;

    // #[ORM\Column(type: Types::ARRAY)]
    // private ?array $choiceSubscriber = [
    //     "Free" => [
    //         'abo' => "Gratuit",
    //         'price' => "0",
    //         'premium' => false,
    //     ],
    //     "Premium" => [
    //         'abo' => "Premium",
    //         'price' => "2",
    //         'premium' => true,
    //     ],
    //     "Legendary" => [
    //         'abo' => "Légendaire",
    //         'price' => "5",
    //         'legendary' => true,
    //     ]
    // ];

    // #[ORM\Column(type: Types::ARRAY)]
    // private array $choiceSubscription = [
    //     "Free" => [
    //         'abo' => "Gratuit",
    //         'price' => "0",
    //         'premium' => false,
    //     ],
    //     "Premium" => [
    //         'abo' => "Premium",
    //         'price' => "2",
    //         'premium' => true,
    //     ],
    //     "Legendary" => [
    //         'abo' => "Légendaire",
    //         'price' => "5",
    //         'legendary' => true,
    //     ]
    // ];

    public function __construct()
    {
        $this->subscriber = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSubscriber(): Collection
    {
        return $this->subscriber;
    }

    public function addSubscriber(User $subscriber): static
    {
        if (!$this->subscriber->contains($subscriber)) {
            $this->subscriber->add($subscriber);
            $subscriber->setSubscription($this);
        }

        return $this;
    }

    public function removeSubscriber(User $subscriber): static
    {
        if ($this->subscriber->removeElement($subscriber)) {
            // set the owning side to null (unless already changed)
            if ($subscriber->getSubscription() === $this) {
                $subscriber->setSubscription(null);
            }
        }

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getIsSubscriber(): bool
    {
        return $this->isSubscriber;
    }

    public function setIsSubscriber(bool $isSubscriber): self
    {
        $this->isSubscriber = $isSubscriber;

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(string $stripeId): static
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    public function getCurrentPeriodStart(): ?\DateTimeInterface
    {
        return $this->currentPeriodStart;
    }

    public function setCurrentPeriodStart(\DateTimeInterface $currentPeriodStart): static
    {
        $this->currentPeriodStart = $currentPeriodStart;

        return $this;
    }

    public function getCurrentPeriodEnd(): ?\DateTimeInterface
    {
        return $this->currentPeriodEnd;
    }

    public function setCurrentPeriodEnd(\DateTimeInterface $currentPeriodEnd): static
    {
        $this->currentPeriodEnd = $currentPeriodEnd;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): static
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setSubscription($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getSubscription() === $this) {
                $invoice->setSubscription(null);
            }
        }

        return $this;
    }

}

<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: 'tbl_users')]
#[ApiResource]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'transactionId', length: 50)]
    private ?string $transactionId = null;

    #[ORM\Column(name: 'fileNumber', length: 10)]
    private ?string $fileNumber = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 7)]
    private ?string $gender = null;

    #[ORM\Column(length: 20)]
    private ?string $department = null;

    #[ORM\Column(length: 12)]
    private ?string $phone = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $thrif = null;

    #[ORM\Column(name: 'specialSavings')]
    private ?int $specialSavings = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(options: ['default' => 1])]
    private ?int $status = null;

    #[ORM\OneToOne(targetEntity: User::class, mappedBy: 'member')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): static
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getFileNumber(): ?string
    {
        return $this->fileNumber;
    }

    public function setFileNumber(string $fileNumber): static
    {
        $this->fileNumber = $fileNumber;

        return $this;
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getThrif(): ?int
    {
        return $this->thrif;
    }

    public function setThrif(int $thrif): static
    {
        $this->thrif = $thrif;

        return $this;
    }

    public function getSpecialSavings(): ?int
    {
        return $this->specialSavings;
    }

    public function setSpecialSavings(int $specialSavings): static
    {
        $this->specialSavings = $specialSavings;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}

<?php
declare(strict_types=1);

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\BirthDate;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\HashedPassword;
use App\Domain\User\ValueObjects\Name;
use App\Domain\User\Enums\UserRole;
use App\Domain\User\Enums\Gender;

final class UserEntity
{
    private function __construct(
        private ?int $id,
        private Name $name,
        private Email $email,
        private ?\DateTimeImmutable $emailVerifiedAt = null,
        private UserRole $role,
        private ?BirthDate $birthdate,
        private Gender $gender,
        private ?string $image = null,
        private ?string $bio = 'Between the pages of a book, I found myself 📚✨',
        private ?HashedPassword $password = null,
    ) {}

    // ========================
    // 🔥 Factories
    // ========================

    public static function create(
        Name $name,
        Email $email,
        Gender $gender,
        HashedPassword $password,
        UserRole $role,
        ?BirthDate $birthdate = null
    ): self {
        if ($role === UserRole::ADMIN) {
            throw new \DomainException('Cannot register as an administrator.');
        }
        if ($role === UserRole::AUTHOR) {
            if ($birthdate === null || !$birthdate->isAdult()) {
                throw new \DomainException('Authors must be adults (18+).');
            }
        }
        return new self(
            id: null,
            name: $name,
            email: $email,
            emailVerifiedAt: null,
            role: $role,
            birthdate: $birthdate,
            gender: $gender,
            password: $password
        );
    }

    public static function fromPersistence(
        int $id,
        Name $name,
        Email $email,
        ?\DateTimeImmutable $emailVerifiedAt,
        UserRole $role,
        ?BirthDate $birthdate,
        Gender $gender,
        ?string $image,
        ?string $bio,
        ?HashedPassword $password = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            email: $email,
            emailVerifiedAt: $emailVerifiedAt,
            role: $role,
            birthdate: $birthdate,
            gender: $gender,
            image: $image,
            bio: $bio,
            password: $password,
        );
    }

    // ========================
    // 🧠 Business Logic
    // ========================

    public function changeEmail(Email $email): void
    {
        if ($this->email->equals($email)) {
            throw new \DomainException('New email must be different from current email');
        }

        $this->email = $email;
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeBirthdate(?BirthDate $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function changeGender(Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
    }

    public function updateBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    public function updateImage(?string $image): void
    {
        $this->image = $image;
    }

    public function promoteToAuthor(): void
    {
        if ($this->birthdate === null || !$this->birthdate->isAdult()) {
            throw new \DomainException('User must be an adult to become an author');
        }

        if ($this->role !== UserRole::CUSTOMER) {
            throw new \DomainException('Only regular users can be promoted to author');
        }

        $this->role = UserRole::AUTHOR;
    }

    public function makeAdmin(): void
    {
        if ($this->role !== UserRole::AUTHOR) {
            throw new \DomainException('Only authors can be promoted to admin');
        }

        $this->role = UserRole::ADMIN;
    }

    public function demoteToUser(): void
    {
        if ($this->role !== UserRole::ADMIN) {
            throw new \DomainException('Only admins can be demoted');
        }

        $this->role = UserRole::CUSTOMER;
    }

    public function verifyEmail(): void
    {
        if ($this->isEmailVerified()) {
            throw new \DomainException('Email already verified');
        }
        $this->emailVerifiedAt = new \DateTimeImmutable();
    }
    public function isEmailVerified(): bool {return $this->emailVerifiedAt !== null; }


    // ========================
    // 🧠 Queries / Helpers
    // ========================

    public function isNew(): bool
    {
        return $this->id === null;
    }

    public function sameIdentityAs(self $other): bool
    {
        return $this->id !== null && $this->id === $other->getId();
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isAuthor(): bool
    {
        return $this->role === UserRole::AUTHOR;
    }

    public function getAge(): ?int
    {
        return $this->birthdate?->age();
    }

    public function verifyPassword(string $plainPassword): bool
    {
        return $this->password !== null && $this->password->verify($plainPassword);
    }

    // ========================
    // 🧾 Getters
    // ========================

    public function getId(): ?int { return $this->id; }
    public function getName(): Name { return $this->name; }
    public function getEmail(): Email { return $this->email; }
    public function getRole(): UserRole { return $this->role; }
    public function getBirthdate(): ?BirthDate { return $this->birthdate; }
    public function getGender(): Gender { return $this->gender; }
    public function getImage(): ?string {
        if ($this->image !== null && $this->image !== '') {
            return $this->image;
        }
        return $this->gender === Gender::MALE
            ? 'images/man-avatar.png'
            : 'images/woman-avatar.png';
    }
    public function getBio(): ?string { return $this->bio; }
    public function getPasswordHash(): ?HashedPassword
    {
        return $this->password;
    }
    public function getEmailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }
    public function __toString(): string
    {
        return sprintf('User[%s]: %s (%s)', $this->id ?? 'NEW', $this->name, $this->email);
    }
}

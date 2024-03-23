<?php

namespace App\Entity;

use Assert\Image;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nickname = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Job $job = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastVisiteAt = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Shop $shop = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'image')]
    #[Assert\File(
        maxSize: '2M',
        extensions: ['jpeg','jpg','png'],
        extensionsMessage: 'Format image non valide: jpeg, jpg, png',
    )]
    #[Assert\Image(
        minWidth: 200,
        maxWidth: 800,
        minHeight: 200,
        maxHeight: 1200,
    )]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'name', targetEntity: Elu::class)]
    private Collection $elus;

    #[ORM\OneToMany(mappedBy: 'name', targetEntity: Desk::class)]
    private Collection $desks;

    #[ORM\OneToMany(mappedBy: 'name', targetEntity: Paiement::class)]
    private Collection $paiements;

    #[ORM\Column(length: 18)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'updatedBy', targetEntity: Elu::class)]
    private Collection $updatedElus;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SexStatus $sex = null;

    #[ORM\Column]
    private ?bool $isAgreeTerms = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Contact::class)]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'answeredBy', targetEntity: Contact::class)]
    private Collection $answers;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Invitation $invitation = null;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Invitation::class)]
    private Collection $invitations;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'updatedBy', targetEntity: Post::class)]
    private Collection $posts_updated;

    public function __construct()
    {
        $this->elus = new ArrayCollection();
        $this->desks = new ArrayCollection();
        $this->paiements = new ArrayCollection();
        $this->updatedElus = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->posts_updated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastVisiteAt(): ?\DateTimeImmutable
    {
        return $this->lastVisiteAt;
    }

    public function setLastVisiteAt(\DateTimeImmutable $lastVisiteAt): static
    {
        $this->lastVisiteAt = $lastVisiteAt;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): static
    {
        $this->shop = $shop;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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

    /**
     * @return Collection<int, Elu>
     */
    public function getElus(): Collection
    {
        return $this->elus;
    }

    public function addElu(Elu $elu): static
    {
        if (!$this->elus->contains($elu)) {
            $this->elus->add($elu);
            $elu->setName($this);
        }

        return $this;
    }

    public function removeElu(Elu $elu): static
    {
        if ($this->elus->removeElement($elu)) {
            // set the owning side to null (unless already changed)
            if ($elu->getName() === $this) {
                $elu->setName(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Desk>
     */
    public function getDesks(): Collection
    {
        return $this->desks;
    }

    public function addDesk(Desk $desk): static
    {
        if (!$this->desks->contains($desk)) {
            $this->desks->add($desk);
            $desk->setName($this);
        }

        return $this;
    }

    public function removeDesk(Desk $desk): static
    {
        if ($this->desks->removeElement($desk)) {
            // set the owning side to null (unless already changed)
            if ($desk->getName() === $this) {
                $desk->setName(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): static
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setName($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): static
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getName() === $this) {
                $paiement->setName(null);
            }
        }

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

    public function __toString()
    {
        return $this->firstname.' '.$this->lastname ?? $this->email;
    }

    /**
     * @return Collection<int, Elu>
     */
    public function getUpdatedElus(): Collection
    {
        return $this->updatedElus;
    }

    public function addUpdatedElu(Elu $updatedElu): static
    {
        if (!$this->updatedElus->contains($updatedElu)) {
            $this->updatedElus->add($updatedElu);
            $updatedElu->setUpdatedBy($this);
        }

        return $this;
    }

    public function removeUpdatedElu(Elu $updatedElu): static
    {
        if ($this->updatedElus->removeElement($updatedElu)) {
            // set the owning side to null (unless already changed)
            if ($updatedElu->getUpdatedBy() === $this) {
                $updatedElu->setUpdatedBy(null);
            }
        }

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSex(): ?SexStatus
    {
        return $this->sex;
    }

    public function setSex(?SexStatus $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getIsAgreeTerms(): ?bool
    {
        return $this->isAgreeTerms;
    }

    public function setIsAgreeTerms(bool $isAgreeTerms): static
    {
        $this->isAgreeTerms = $isAgreeTerms;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Contact $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setAnsweredBy($this);
        }

        return $this;
    }

    public function removeAnswer(Contact $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getAnsweredBy() === $this) {
                $answer->setAnsweredBy(null);
            }
        }

        return $this;
    }

    public function getInvitation(): ?Invitation
    {
        return $this->invitation;
    }

    public function setInvitation(?Invitation $invitation): static
    {
        // unset the owning side of the relation if necessary
        if ($invitation === null && $this->invitation !== null) {
            $this->invitation->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($invitation !== null && $invitation->getUser() !== $this) {
            $invitation->setUser($this);
        }

        $this->invitation = $invitation;

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setCreatedBy($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getCreatedBy() === $this) {
                $invitation->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCreatedBy($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCreatedBy() === $this) {
                $post->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPostsUpdated(): Collection
    {
        return $this->posts_updated;
    }

    public function addPostsUpdated(Post $postsUpdated): static
    {
        if (!$this->posts_updated->contains($postsUpdated)) {
            $this->posts_updated->add($postsUpdated);
            $postsUpdated->setUpdatedBy($this);
        }

        return $this;
    }

    public function removePostsUpdated(Post $postsUpdated): static
    {
        if ($this->posts_updated->removeElement($postsUpdated)) {
            // set the owning side to null (unless already changed)
            if ($postsUpdated->getUpdatedBy() === $this) {
                $postsUpdated->setUpdatedBy(null);
            }
        }

        return $this;
    }
}

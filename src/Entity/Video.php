<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 * @ORM\Table(name="video")
 * @ORM\HasLifecycleCallbacks
 */
class Video
{
    use TimesTampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le titre de la vidéo ne peut pas être vide")
     * @Assert\Length(min=3,minMessage="Le titre doit comprendre minimum {{ limit }} caractère")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="Le lien de la vidéo ne peut pas être vide")
     */
    private $videoLink;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description de la vidéo ne peut pas être vide")
     * @Assert\Length(min=10,minMessage="La description doit comprendre minimum {{ limit }} caractère")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ispremium;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): self
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isIspremium(): ?bool
    {
        return $this->ispremium;
    }

    public function setIspremium(?bool $ispremium): self
    {
        $this->ispremium = $ispremium;

        return $this;
    }
}

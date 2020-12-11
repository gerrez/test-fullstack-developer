<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="notes")
     */
    private $document;

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function setNoteData(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $title = !empty($data['title']) ? $data['title'] : '';
        $body = !empty($data['body']) ? $data['body'] : '';

        $this->setTitle($title);
        $this->setBody($body);
    }

    /**
     * @param ValidatorInterface $validator
     * @return array
     */
    public function validateInput(ValidatorInterface $validator): array
    {
        $errorMessages = [];

        $errors = $validator->validate($this);
        if (count($errors) > 0) {

            foreach ($errors as $key => $error) {
                $errorMessages[$errors->get($key)->getPropertyPath()] = $errors->get($key)->getMessage();
            }
        }

        return $errorMessages;
    }
}
<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\DocumentRepository;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NoteController extends AbstractController
{
    private $noteRepository;
    private $documentRepository;

    public function __construct(NoteRepository $noteRepository, DocumentRepository $documentRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->documentRepository = $documentRepository;
    }
    /**
     * @Route("/notes", name="getNotes", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $notes = $this->noteRepository->findAll();
        return new Response($this->serializeData($notes), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/notes/{id}", name="getNote", methods={"GET"})
     * @return Response
     */
    public function show(Request $request): Response
    {
        $note = $this->noteRepository->find($request->get('id'));

        if (is_null($note)) {
            return new Response($this->serializeData(["message"=>"missing data"]), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        return new Response($this->serializeData($note), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/notes", name="createNote", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $note = new Note();
        $note->setNoteData($request);

        $errors = $note->validateInput($validator);

        if (!empty($errors)) {
            return new Response($this->serializeData($errors), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        };

        $this->noteRepository->saveOrUpdateNote($note);
        return new Response($this->serializeData($note), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/notes/{id}", name="updateNote", methods={"PUT"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function update(Request $request, ValidatorInterface $validator): Response
    {
        $note = $this->noteRepository->findOneBy(['id' => $request->get('id')]);

        if (is_null($note)) {
            return new Response($this->serializeData(["message"=>"missing data"]), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $note->setNoteData($request);

        $errors = $note->validateInput($validator);

        if (!empty($errors)) {
            return new Response($this->serializeData($errors), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        };

        $updatedNote = $this->noteRepository->saveOrUpdateNote($note);

        return new Response($this->serializeData($updatedNote), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/notes/{id}", name="deleteNote", methods={"DELETE"})
     * @return Response
     */
    public function delete(): Response
    {
        return new Response($this->serializeData(['message' => "Delete not allowed"]), Response::HTTP_FORBIDDEN, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/notes/{noteId}/documents/{docId}", name="setNoteRelation", methods={"PATCH"})
     * @param Request $request
     * @return Response
     */
    public function setRelation(Request $request): Response
    {
        $document = $this->documentRepository->findOneBy(['id' => $request->get('docId')]);
        $note = $this->noteRepository->findOneBy(['id' => $request->get('noteId')]);

        if (is_null($document) || is_null($note)) {
            return new Response($this->serializeData(["message"=>"missing data"]), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $note->setDocument($document);
        $this->noteRepository->saveOrUpdateNote($note);

        return new Response($this->serializeData($note), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Note|[] $note
     * @return string
     */
    private function serializeData($note): string
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonObject = $serializer->serialize($note, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return $jsonObject;
    }
}

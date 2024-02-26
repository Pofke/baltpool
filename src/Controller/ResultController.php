<?php

declare(strict_types=1);

namespace App\Controller;

use App\Module\UseCase\Result\GetResultsUseCase;
use App\Module\UseCase\Result\GetResultUseCase;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/result')]
class ResultController extends BaseController
{
    public function __construct(
        private readonly GetResultsUseCase $getResultsUseCase,
        private readonly GetResultUseCase $getResultUseCase,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/', name: 'app_result_index', methods: ['GET'])]
    public function index(): Response
    {
        $content = $this->serializer->serialize(
            $this->getResultsUseCase->handle(),
            'json',
            ['groups' => ['result:list']]
        );

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_link_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        try {
            $result = $this->getResultUseCase->handle($id);
        } catch (EntityNotFoundException $exception) {
            throw $this->createNotFoundException($exception->getMessage());
        }

        $content = $this->serializer->serialize(
            $result,
            'json',
            ['groups' => ['result:item']]
        );

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}

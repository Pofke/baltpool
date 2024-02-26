<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AlreadyExistsException;
use App\Form\LinkType;
use App\Module\Dto\LinkDto;
use App\Module\UseCase\Link\CreateLinkUseCase;
use App\Module\UseCase\Link\DeleteLinkUseCase;
use App\Module\UseCase\Link\GetLinksUseCase;
use App\Module\UseCase\Link\GetLinkUseCase;
use App\Module\UseCase\Link\UpdateLinkUseCase;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/link')]
class LinkController extends BaseController
{
    public function __construct(
        private readonly GetLinksUseCase $getLinksUseCase,
        private readonly GetLinkUseCase $getLinkUseCase,
        private readonly CreateLinkUseCase $createLinkUseCase,
        private readonly UpdateLinkUseCase $updateLinkUseCase,
        private readonly DeleteLinkUseCase $deleteLinkUseCase,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/', name: 'app_link_index', methods: ['GET'])]
    public function index(): Response
    {
        $content = $this->serializer->serialize(
            $this->getLinksUseCase->handle(),
            'json',
            ['groups' => ['link:list']]
        );

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_link_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        try {
            $link = $this->getLinkUseCase->handle($id);
        } catch (EntityNotFoundException $exception) {
            throw $this->createNotFoundException($exception->getMessage());
        }

        $content = $this->serializer->serialize(
            $link,
            'json',
            ['groups' => ['link:item']]
        );

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/new', name: 'app_link_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $link = new LinkDto();

        $form = $this->createForm(LinkType::class, $link);
        $form->submit($this->decodeJson($request->getContent()));
        $this->validateForm($form);

        try {
            $result = $this->createLinkUseCase->handle($link);
        } catch (AlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        $content = $this->serializer->serialize($result, 'json', ['groups' => ['link:create']]);

        return new Response($content, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_link_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, int $id): Response
    {
        $link = new LinkDto();

        $form = $this->createForm(LinkType::class, $link);
        $form->submit($this->decodeJson($request->getContent()));
        $this->validateForm($form);

        try {
            $result = $this->updateLinkUseCase->handle($id, $link);
        } catch (EntityNotFoundException $exception) {
            throw $this->createNotFoundException($exception->getMessage());
        } catch (AlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        $content = $this->serializer->serialize($result, 'json', ['groups' => ['link:create']]);

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_link_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        try {
            $this->deleteLinkUseCase->handle($id);
        } catch (EntityNotFoundException $exception) {
            throw $this->createNotFoundException(previous: $exception);
        }

        return new Response();
    }
}

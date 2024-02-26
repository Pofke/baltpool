<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AlreadyExistsException;
use App\Form\KeywordType;
use App\Module\Dto\KeywordDto;
use App\Module\UseCase\Keyword\CreateKeywordUseCase;
use App\Module\UseCase\Keyword\DeleteKeywordUseCase;
use App\Module\UseCase\Keyword\GetKeywordsUseCase;
use App\Module\UseCase\Keyword\GetKeywordUseCase;
use App\Module\UseCase\Keyword\UpdateKeywordUseCase;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/keyword')]
class KeywordController extends BaseController
{
    public function __construct(
        private readonly GetKeywordsUseCase $getKeywordsUseCase,
        private readonly GetKeywordUseCase $getKeywordUseCase,
        private readonly CreateKeywordUseCase $createKeywordUseCase,
        private readonly UpdateKeywordUseCase $updateKeywordUseCase,
        private readonly DeleteKeywordUseCase $deleteKeywordUseCase,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/', name: 'app_keyword_index', methods: ['GET'])]
    public function index(): Response
    {
        $content = $this->serializer->serialize(
            $this->getKeywordsUseCase->handle(),
            'json',
            ['groups' => ['keyword:list']]
        );

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_keyword_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        try {
            $keyword = $this->getKeywordUseCase->handle($id);
        } catch (EntityNotFoundException $exception) {
            throw $this->createNotFoundException($exception->getMessage());
        }

        $content = $this->serializer->serialize(
            $keyword,
            'json',
            ['groups' => ['keyword:item']]
        );

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/new', name: 'app_keyword_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $keywordDto = new KeywordDto();

        $form = $this->createForm(KeywordType::class, $keywordDto);
        $form->submit($this->decodeJson($request->getContent()));
        $this->validateForm($form);

        try {
            $result = $this->createKeywordUseCase->handle($keywordDto);
        } catch (AlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        $content = $this->serializer->serialize($result, 'json', ['groups' => ['keyword:create']]);

        return new Response($content, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_keyword_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, int $id): Response
    {
        $keywordDto = new KeywordDto();

        $form = $this->createForm(KeywordType::class, $keywordDto);
        $form->submit($this->decodeJson($request->getContent()));
        $this->validateForm($form);

        try {
            $result = $this->updateKeywordUseCase->handle($id, $keywordDto);
        } catch (EntityNotFoundException $exception) {
            throw $this->createNotFoundException($exception->getMessage());
        } catch (AlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        $content = $this->serializer->serialize($result, 'json', ['groups' => ['keyword:create']]);

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_keyword_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        try {
            $this->deleteKeywordUseCase->handle($id);
        } catch (EntityNotFoundException $exception) {
            throw $this->createNotFoundException(previous: $exception);
        }

        return new Response();
    }
}

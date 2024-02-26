<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    protected function decodeJson(string $json): array
    {
        $jsonData = json_decode($json, true);

        if ($jsonData === null) {
            throw new HttpException(422, 'Unprocessable Entity: Invalid request body');
        }

        return (array)$jsonData;
    }

    protected function validateForm(FormInterface $form): void
    {
        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new HttpException(422, (string)json_encode($this->getFormErrors($form)));
        }
    }

    /**
     * @return array<int, string>
     */
    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        /** @var FormError $error */
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }
}

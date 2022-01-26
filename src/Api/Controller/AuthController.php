<?php

namespace App\Api\Controller;

use App\Data\PasswordResetRequestData;
use App\Exception\OngoingPasswordResetException;
use App\Exception\UserNotFoundException;
use App\Service\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AuthController extends AbstractController
{
    private PasswordService $resetService;

    public function __construct(PasswordService $passwordService)
    {
        $this->resetService = $passwordService;
    }

    public function reset(Request $request, DenormalizerInterface $denormalizer)
    {
        $data = json_decode((string) $request->getContent(), true);
        $passwordResetData = $denormalizer->denormalize($data, PasswordResetRequestData::class);

        try {
            $this->resetService->resetPassword($passwordResetData);
        } catch (UserNotFoundException $exception) {
            return new JsonResponse(['error' => $exception->getMessageKey()], Response::HTTP_UNAUTHORIZED);
        } catch (OngoingPasswordResetException $exception) {
            return new JsonResponse(['error' => $exception->getMessageKey()], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}

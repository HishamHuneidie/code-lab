<?php

namespace Hisham\CodeLab\Context\User\Api;

use Hisham\CodeLab\Common\Exception\CommonException;
use Hisham\CodeLab\Context\User\Application\UseCase\SaveUser\SaveUser;
use Hisham\CodeLab\Context\User\Application\UseCase\SaveUser\SaveUserCommand;
use Hisham\CodeLab\Context\User\Application\UseCase\UserById\UserById;
use Hisham\CodeLab\Context\User\Application\UseCase\UserList\UserList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users', name: 'api.user.')]
class UserController extends AbstractController
{
    #[Route('/', name: 'findAll', methods: ['GET'])]
    public function list(UserList $userList): JsonResponse
    {
        try {
            $users = $userList->execute();
        } catch (CommonException $e) {
            return $this->json(['errors' => 'Some errors']);
        }

        return $this->json($users);
    }

    #[Route('/{id}', name: 'findById', methods: ['GET'])]
    public function get(string $id, UserById $userById): JsonResponse
    {
        try {
            $user = $userById->execute($id);
        } catch (CommonException $e) {
            return $this->json(['errors' => 'Some errors']);
        }

        return $this->json($user);
    }

    #[Route('/{id?}', name: 'save', methods: ['POST'])]
    public function save(
        #[MapRequestPayload] SaveUserCommand $command,
        SaveUser                             $saveUser,
    ): JsonResponse
    {
        try {
            $saveUser->execute($command);
        } catch (CommonException $e) {
            return $this->json(['errors' => 'Some errors']);
        }

        return $this->json([]);
    }

}

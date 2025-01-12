<?php

namespace Hisham\CodeLab\Context\Watch\Api;

use Hisham\CodeLab\Common\Exception\CommonException;
use Hisham\CodeLab\Context\Watch\Application\Dto\EscapedPathnameVoDto;
use Hisham\CodeLab\Context\Watch\Application\UseCase\GetRfcByPathname\GetRfcByPathname;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/watch', name: 'api.watch.rfcs')]
class RfcController extends AbstractController
{
    #[Route('/{pathname}')]
    public function get(string $pathname, GetRfcByPathname $getRfcByPathname): JsonResponse
    {
        try {
            $rfc = $getRfcByPathname->execute(new EscapedPathnameVoDto($pathname));
        } catch (CommonException $e) {
            return $this->json(['errors' => 'Error getting RFC']);
        }

        return $this->json($rfc);
    }

}

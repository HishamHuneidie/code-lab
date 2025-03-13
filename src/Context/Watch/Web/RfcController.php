<?php

namespace Hisham\CodeLab\Context\Watch\Web;

use Hisham\CodeLab\Common\Exception\CommonException;
use Hisham\CodeLab\Context\Watch\Application\Dto\EscapedPathnameVoDto;
use Hisham\CodeLab\Context\Watch\Application\UseCase\GetRfcByPathname\GetRfcByPathname;
use Hisham\CodeLab\Context\Watch\Application\UseCase\GetRfcList\GetRfcList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/watch/rfcs', name: 'watch.rfcs.')]
final class RfcController extends AbstractController
{
    #[Route('/', name: 'findAll', methods: ['GET'])]
    public function list(GetRfcList $getRfcList): Response
    {
        try {
            $rfcs = $getRfcList->execute();
        } catch (CommonException $e) {
        }

        return $this->render('context/watch/rfcs.html.twig', [
            'rfcs' => $rfcs ?? [],
        ]);
    }

    #[Route('/{pathname}', name: 'findByPathname', methods: ['GET'])]
    public function get(string $pathname, GetRfcByPathname $getRfcByPathname): Response
    {
        try {
            $pathnameVoDto = EscapedPathnameVoDto::create($pathname);
            $rfc = $getRfcByPathname->execute($pathnameVoDto);
        } catch (CommonException $e) {
        }

        return $this->render('context/watch/rfc.html.twig', [
            'rfc' => $rfc ?? [],
        ]);
    }
}

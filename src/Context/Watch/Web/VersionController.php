<?php

namespace Hisham\CodeLab\Context\Watch\Web;

use Hisham\CodeLab\Common\Exception\CommonException;
use Hisham\CodeLab\Context\Watch\Application\UseCase\GetVersionList\GetVersionList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/watch/versions', name: 'watch.versions.')]
class VersionController extends AbstractController
{
    #[Route('/', name: 'findAll', methods: ['GET'])]
    public function list(GetVersionList $getVersionList): Response
    {
        try {
            $versions = $getVersionList->execute();
        } catch (CommonException $e) {
        }

        return $this->render('context/watch/versions.html.twig', [
            'versions' => $versions ?? [],
        ]);
    }

}

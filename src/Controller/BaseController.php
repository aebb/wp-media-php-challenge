<?php

namespace App\Controller;

use App\Service\ContentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    protected ContentService $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/", name="home.get", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function executeHomeGet(Request $request): Response
    {
        return $this->render(
            'base.html.twig',
            [
                'links' => $this->service->getContent($request)
            ]
        );
    }

    /**
     * @Route("/sitemap", name="sitemap.get", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function executeSitemapGet(Request $request): Response
    {
        return $this->render($this->service->getSiteMapTemplate($request));
    }
}

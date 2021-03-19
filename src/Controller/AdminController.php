<?php

namespace App\Controller;

use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    protected AdminService $service;

    public function __construct(AdminService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/admin", name="admin.get", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function executeAdminGet(Request $request): Response
    {
        return $this->render('admin.html.twig', ['task' => $this->service->get($request)]);
    }

    /**
     * @Route("/admin", name="admin.post", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function executeAdminPost(Request $request): Response
    {
        $this->service->post($request);
        return $this->redirectToRoute('admin.get');
    }
}

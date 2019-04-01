<?php


namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ErrorController extends AbstractController
{

    /**
     * @Route("/error",name="app_404_error",methods={"POST","GET"})
     *
     */
    public function NotFoundError()
    {
        return $this->render("errors/404.html.twig");



    }

}
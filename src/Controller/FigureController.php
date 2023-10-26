<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\Type\FigureType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FigureController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/figure/new',name: 'app_figure_new')]
    #[IsGranted('ROLE_USER')]
    public function newFigure(Request $request): Response
    {
        $figure = new Figure();
        $form = $this
            ->createForm(FigureType::class, $figure)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($figure);
                $this->em->flush();

                $this->addFlash(
                    'success',
                    'The snowboard trick was created'
                );

                return $this->redirectToRoute('app_figure_new');

            } catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'The snowboard trick was not created'
                );
            }

        }

        return $this->render('figure/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
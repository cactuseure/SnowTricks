<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\Type\FigureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('figure/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/figure/{id}/edit',name: 'app_figure_edit')]
    #[IsGranted('ROLE_USER')]
    public function editFigure(Figure $figure, Request $request): Response
    {
        $form = $this
            ->createForm(FigureType::class, $figure)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($figure);
                $this->em->flush();

                $this->addFlash(
                    'success',
                    'The snowboard trick was modif'
                );

                return $this->redirectToRoute('app_figure_show',['slug'=>$figure->getSlug()]);

            } catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'The snowboard trick was not created'
                );
            }

        }

        return $this->render('figure/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/figure/{slug}', name: 'app_figure_show')]
    public function show(Figure $figure): Response
    {
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
        ]);
    }

}
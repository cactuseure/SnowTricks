<?php

namespace App\Controller;

use App\Entity\FigureGroup;
use App\Form\Type\FigureGroupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FigureGroupController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {

    }


    #[Route('/figure-group/nouveau',name: 'app_figure_group_new')]
    #[IsGranted('ROLE_USER')]
    public function newFigureGroup(Request $request): Response
    {
        $figureGroup = new FigureGroup();
        $form = $this
            ->createForm(FigureGroupType::class, $figureGroup)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($figureGroup);
                $this->em->flush();

                $this->addFlash(
                    'success',
                    'Le groupe de figure a bien été créée'
                );

                return $this->redirectToRoute('app_home_index');

            } catch (\Exception $e){
                dump($e);
                $this->addFlash(
                    'error',
                    'L\'enregistrement du groupe de figure a échoué'
                );
            }

        }

        return $this->render('figure_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
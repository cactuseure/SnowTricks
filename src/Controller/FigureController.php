<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Figure;
use App\Form\Type\CommentaireType;
use App\Form\Type\FigureType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FigureController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/figure/nouveau',name: 'app_figure_new')]
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
                    'La figure de snowboard a bien été créée'
                );

                return $this->redirectToRoute('app_figure_new');

            } catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'L\'enregistrement de la figure a échoué'
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
                    'La figure de snowboard a été modifiée'
                );

                return $this->redirectToRoute('app_figure_show',['slug'=>$figure->getSlug()]);

            } catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'La figure du snowboard n\'a pas été créée'
                );
            }

        }

        return $this->render('figure/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/figure/{id}/delete',name: 'app_figure_delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(Figure $figure): Response
    {
        $this->em->remove($figure);
        $this->em->flush();

        $this->addFlash(
            'success',
            'La figure du snowboard a été supprimée'
        );

        return $this->redirectToRoute('app_home_index');
    }

    #[Route('/fetch-comments-by-trick', name: 'app_fetch_comments_by_trick', methods: ['POST'])]
    public function commentsPaginationAjax(Request $request): Response
    {
        $perPage = (int) $request->get('perPage', 1);
        $page = (int) $request->get('page', 1);
        $trickId = $request->get('trickId', 2);

        $collection = $this->getPaginatedComments(
            $trickId,
            $perPage,
            $page
        );

        return $this->render('/embed/comment.html.twig', [
            'collection' => $collection,
        ]);
    }

    private function getPaginatedComments(
        int $trickId,
        int $perPage = 9,
        int $currentPage = 1,
    ): array {
        $total = $this->commentaireRepository->findTotalComments($trickId);

        $lastPage = (int) ceil($total / $perPage);
        $page = $currentPage > $lastPage ? 1 : $currentPage;
        $items = $this->commentaireRepository->findPaginatedComments(
            $trickId,
            $perPage,
            ($page * $perPage) - $perPage
        );

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage,
        ];
    }

    #[Route('/figure/{slug}', name: 'app_figure_show')]
    public function show(Figure $figure, Request $request): Response
    {
        $commentaire = new Commentaire();

        $form = $this
            ->createForm(CommentaireType::class, $commentaire)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire
                ->setFigure($figure)
                ->setAuteur($this->getUser());
            $figure->addCommentaire($commentaire);
            $this->em->persist($commentaire);
            $this->em->flush();

            return $this->redirectToRoute('app_figure_show',['slug'=>$figure->getSlug()]);
        }
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
        ]);
    }

}
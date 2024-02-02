<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Figure;
use App\Entity\MediaObject;
use App\Form\Type\CommentaireType;
use App\Form\Type\FigureType;
use App\Repository\CommentaireRepository;
use App\Repository\FigureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FigureController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private readonly CommentaireRepository $commentaireRepository, private readonly FigureRepository $figureRepository)
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
                $mediaObjects = $form->get('pictures')->getData();

                /**
                 * @var MediaObject $mediaObject
                 */
                foreach ($mediaObjects as $mediaObject) {
                    if (null !== ( $mediaObject = $this->uploadFile($mediaObject->getFile(),'tricks'))){
                        $figure->addPicture($mediaObject);
                    }
                }

                $cover = $form->get('cover')->getData();
                if (null !== ( $mediaObject = $this->uploadFile($cover,'tricks'))){
                    $figure->setCover($mediaObject);
                }


                $this->em->persist($figure);
                $this->em->flush();

                $this->addFlash(
                    'success',
                    'La figure de snowboard a bien été créée'
                );

                return $this->redirectToRoute('app_figure_new');

            } catch (\Exception $e){
                dump($e);
                $this->addFlash(
                    'error',
                    'L\'enregistrement de la figure a échoué'
                );
            }

        }

        return $this->render('figure/create.html.twig', [
            'form' => $form->createView(),
            'figure' => $figure
        ]);
    }


    private function uploadFile(?UploadedFile $file, string $folderName): ?MediaObject
    {
        if (!$file instanceof UploadedFile) {
            return null;
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $orignalExtension = $file->guessExtension();
        $newFilename = sprintf('%s-%s.%s', $originalFilename, uniqid(), $orignalExtension);
        $destinationPath = sprintf('%s%s', $this->getParameter('uploads_directory'), $folderName);
        $file->move(
            $destinationPath,
            $newFilename
        );

        return (new MediaObject())
            ->setName($newFilename)
            ->setPath($destinationPath)
            ->setExtension($orignalExtension);
    }

    #[Route('/figure/{id}/edit',name: 'app_figure_edit')]
    #[IsGranted('ROLE_USER')]
    public function editFigure(Figure $figure, Request $request): Response
    {
        $originalPictures = new ArrayCollection();

        // Create an ArrayCollection of the current MediaObject objects in the database
        foreach ($figure->getPictures() as $picture) {
            $originalPictures->add($picture);
        }


        $form = $this
            ->createForm(FigureType::class, $figure)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*try {

                foreach ($originalPictures as $picture) {
                    if (false === $figure->getPictures()->contains($picture)) {
                        $figure->removePicture($picture);
                    }
                }

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
            }*/
            try {
                $mediaObjects = $form->get('pictures')->getData();

                /**
                 * @var MediaObject $mediaObject
                 */
                foreach ($mediaObjects as $mediaObject) {
                    if (null !== ( $mediaObject = $this->uploadFile($mediaObject->getFile(),'tricks'))){
                        $figure->addPicture($mediaObject);
                    }
                }

                $cover = $form->get('cover')->getData();
                if (null !== ( $mediaObject = $this->uploadFile($cover,'tricks'))){
                    $figure->setCover($mediaObject);
                }

                /*$mediaObjects = $form->get('pictures')->getData();
               @var MediaObject $picture
                foreach ($mediaObjects as $picture) {
                    if (false === $figure->getPictures()->contains($picture)) {
                        $figure->removePicture($picture);
                    }
                }*/

                $this->em->persist($figure);
                $this->em->flush();

                $this->addFlash(
                    'success',
                    'La figure de snowboard a été modifiée'
                );

                return $this->redirectToRoute('app_figure_show', ['slug' => $figure->getSlug()]);

            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'La figure du snowboard n\'a pas été modifiée'
                );

                return $this->redirectToRoute('app_figure_edit');
            }
        }

        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),
            'pictures' => $figure->getPictures(),
            'figure' => $figure
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



    #[Route('/figure/{id}/delete-media/{idMedia}', name: 'app_remove_media_from_figure')]
    #[IsGranted('ROLE_USER')]
    public function deleteMediaObject(Figure $figure, string $idMedia): Response
    {
        $mediaObjectRepository = $this->em->getRepository(MediaObject::class);
        $mediaObject = $mediaObjectRepository->find($idMedia);
        $figure->removePicture($mediaObject);
        $this->em->flush();

        $this->addFlash(
            'success',
            'La figure du snowboard a été mise à jour avec succès'
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


    #[Route('/fetch-tricks', name: 'app_fetch_tricks')]
    public function tricksPaginationAjax(Request $request): Response
    {
        $perPage = (int)$request->get('perPage', 1);
        $page = (int)$request->get('page', 1);

        $collection = $this->getPaginatedTricks(
            $perPage,
            $page
        );
        dump($collection);
        return $this->render('/embed/trick_miniature.html.twig', [
            'collection' => $collection,
        ]);
    }

    private function getPaginatedTricks(
        int $perPage = 9,
        int $currentPage = 1,
    ): array
    {
        $total = $this->figureRepository->findTotalTricks();

        $lastPage = (int)ceil($total / $perPage);
        $page = $currentPage > $lastPage ? 1 : $currentPage;
        $items = $this->figureRepository->findPaginatedTricks(
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

            $this->addFlash(
                'success',
                'Commentaire publié avec succès'
            );

            return $this->redirectToRoute('app_figure_show',['slug'=>$figure->getSlug()]);
        }
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
        ]);
    }

}
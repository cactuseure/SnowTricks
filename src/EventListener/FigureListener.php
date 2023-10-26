<?php

namespace App\EventListener;

use App\Entity\Figure;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureListener
{
    public function __construct(private readonly SluggerInterface $slugger, private readonly Security $security)
    {
    }

    #[ORM\PrePersist]
    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (!$object instanceof Figure) {
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $object->setOwner($user);
        $object->setFirstImage('/uploads/figures/defaut.png');
        $slug = $this->slugger->slug($object->getTitle())->lower();
        $object->setSlug($this->makeSlugUnique($slug, $args));
    }

    private function makeSlugUnique($slug, LifecycleEventArgs $args)
    {
        $em = $args->getObjectManager();
        $repository = $em->getRepository(Figure::class);

        $originalSlug = $slug;
        $counter = 1;

        while ($repository->findOneBy(['slug' => $slug])) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
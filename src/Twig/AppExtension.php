<?php

namespace App\Twig;

use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getHeaderData', [$this, 'getHeaderData']),
        ];
    }

    public function getHeaderData(): array
    {
        $isLoggedIn = $this->security->isGranted('IS_AUTHENTICATED_FULLY');

        return [
            'isLoggedIn' => $isLoggedIn,
        ];
    }
}

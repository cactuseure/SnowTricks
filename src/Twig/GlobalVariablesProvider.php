<?php

namespace App\Twig;

use Symfony\Component\Security\Core\Security;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalVariablesProvider implements RuntimeExtensionInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getHeaderData(): array
    {
        $isLoggedIn = $this->security->isGranted('IS_AUTHENTICATED_FULLY');

        return [
            'isLoggedIn' => $isLoggedIn,
        ];
    }
}

<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class YoutubeThumbnailExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('getThumbnail', [$this, 'getThumbnail'])
        ];
    }

    public function getThumbnail($html): string
    {
        if ($this->isYoutube($html)) {
            return $this->getYoutubeThumbnail($this->getYoutubeId($html));
        } elseif ($this->isYoutubeUrl($html)) {
            return $this->getYoutubeThumbnail($this->getYoutubeIdFromUrl($html));
        }

        return "https://placehold.co/600x400?text=Thumbnail+Error";
    }

    private function isYoutube($html): bool
    {
        $pattern = '/https?:\/\/(?:www\.)?youtube\.com\/embed\/[\w-]+/i';
        preg_match($pattern, $html, $matches);

        return !empty($matches);
    }

    private function isYoutubeUrl($url): bool
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);

        return !empty($matches);
    }

    private function getYoutubeId($iframe): string
    {
        preg_match('/src="([^"]+)"/', $iframe, $matches);
        $srcAttribute = $matches[1] ?? '';

        preg_match('/embed\/([a-zA-Z0-9_-]+)/', $srcAttribute, $videoIdMatches);
        $videoId = $videoIdMatches[1] ?? '';

        return $videoId;
    }

    private function getYoutubeIdFromUrl($url): string
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);

        return $matches[1] ?? '';
    }

    private function getYoutubeThumbnail($id): string
    {
        return "https://img.youtube.com/vi/$id/0.jpg";
    }
}

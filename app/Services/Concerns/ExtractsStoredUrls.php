<?php

namespace App\Services\Concerns;

/**
 * Shared helpers to find and parse Cloudinary delivery URLs inside stored
 * strings (columns, JSON payloads, rich-text HTML).
 */
trait ExtractsStoredUrls
{
    /**
     * @return list<string>
     */
    protected function extractUrlsFromString(string $raw): array
    {
        $raw = trim($raw);

        if ($raw === '' || ! str_contains($raw, 'http')) {
            return [];
        }

        if (preg_match_all('#https?://[^\s"\'<>]+#i', $raw, $matches) === false) {
            return [];
        }

        $found = [];

        foreach ($matches[0] as $match) {
            $match = rtrim($match, '.,;)]}');

            if ($match !== '') {
                $found[] = $match;
            }
        }

        return $found;
    }

    protected function cloudNameFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $segments = array_values(array_filter(explode('/', $path)));

        return $segments[0] ?? '(unknown)';
    }

    protected function resourceTypeFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $segments = array_values(array_filter(explode('/', $path)));
        $type = $segments[1] ?? 'image';

        return in_array($type, ['image', 'video', 'raw'], true) ? $type : 'image';
    }

    /**
     * Conservative check: a comma always means a transformation chain, and a
     * leading param segment (w_500, f_auto, ...) means a single transform.
     * A public_id folder that literally looks like a param is a false positive
     * by design — callers flag it for human review.
     */
    protected function hasTransformation(string $url): bool
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $pos = strpos($path, '/upload/');

        if ($pos === false) {
            return false;
        }

        foreach (array_filter(explode('/', substr($path, $pos + 8))) as $segment) {
            if (preg_match('/^v\d+$/', $segment)) {
                continue;
            }

            return $this->isTransformSegment($segment);
        }

        return false;
    }

    protected function isTransformSegment(string $segment): bool
    {
        if ($segment === '' || preg_match('/^v\d+$/', $segment)) {
            return false;
        }

        if (str_contains($segment, ',')) {
            return true;
        }

        return (bool) preg_match('/^(w|h|c|g|x|y|r|q|f|e|dpr|o|bo|b|a|t|fl|dl|l|u|pg|vs|du|so|eo|vc|ac|af|cs|d|fn|ki)_/i', $segment);
    }

    /**
     * Best-effort public_id that also understands stored transformation URLs.
     * CloudinaryService::extractPublicId() is intentionally left untouched.
     */
    protected function extractPublicIdSmart(string $url): ?string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $pos = strpos($path, '/upload/');

        if ($pos === false) {
            return null;
        }

        $parts = [];

        foreach (array_filter(explode('/', substr($path, $pos + 8))) as $segment) {
            if (preg_match('/^v\d+$/', $segment) || $this->isTransformSegment($segment)) {
                continue;
            }

            $parts[] = $segment;
        }

        if ($parts === []) {
            return null;
        }

        $last = array_pop($parts);
        $dot = strrpos($last, '.');

        if ($dot !== false) {
            $last = substr($last, 0, $dot);
        }

        $parts[] = $last;
        $publicId = implode('/', $parts);

        return $publicId !== '' ? $publicId : null;
    }

    /**
     * Leading transformation segments of a delivery URL (for rebuilds).
     *
     * @return list<string>
     */
    protected function transformSegments(string $url): array
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $pos = strpos($path, '/upload/');

        if ($pos === false) {
            return [];
        }

        $segments = [];

        foreach (array_filter(explode('/', substr($path, $pos + 8))) as $segment) {
            if (preg_match('/^v\d+$/', $segment)) {
                break;
            }

            if (! $this->isTransformSegment($segment)) {
                break;
            }

            $segments[] = $segment;
        }

        return $segments;
    }

    /**
     * Rebuild a delivery URL on another public_id, keeping the original
     * transformation chain. The version segment is dropped on purpose —
     * versionless URLs deliver the latest asset.
     */
    protected function rebuildUrl(string $cloud, array $transforms, string $publicId, ?string $ext, string $resourceType = 'image'): string
    {
        $url = 'https://res.cloudinary.com/'.$cloud.'/'.$resourceType.'/upload/';

        if ($transforms !== []) {
            $url .= implode('/', $transforms).'/';
        }

        $url .= $publicId;

        if ($ext !== null && $ext !== '') {
            $url .= '.'.$ext;
        }

        return $url;
    }
}

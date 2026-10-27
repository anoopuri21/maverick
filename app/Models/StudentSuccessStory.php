<?php

namespace App\Models;

use App\Concerns\HasMediaAssets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSuccessStory extends Model
{
    use HasMediaAssets;
    use SoftDeletes;

    private const INSTITUTIONS = [
        'International American University (IAU); Girne American University & Rushford Business School (RBS)',
        'University for the Creative Arts (UCA)',
        'University for the Creative Arts',
        'University of Gloucestershire (UOG)',
        'University of Gloucestershire',
        'International American University (IAU)',
        'Rushford Business School & Girne American University',
        'Charisma University & Rushford Business School',
        'GAU & Rushford Business School',
        'Girne American University',
        'Rushford Business School (RBS)',
        'Rushford Business School',
    ];

    private const NATIONALITIES = [
        'Sri Lanka',
        'Bangladesh',
        'Philippine',
        'Palestine',
        'Pakistan',
        'Jamaica',
        'Myanmar',
        'Uganda',
        'Jordan',
        'Canada',
        'France',
        'Malta',
        'Syria',
        'Egypt',
        'British',
        'Indian',
        'India',
    ];

    protected $fillable = [
        'name',
        'role',
        'quote',
        'photo',
        'photo_asset_id',
        'stars',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'stars' => 'integer',
    ];

    public function cardPayload(): array
    {
        $name = $this->name ?? '';
        $metadata = $this->parseRoleMetadata();

        return [
            'name' => $name,
            'course' => $metadata['course'],
            'university' => $metadata['university'],
            'nationality' => $metadata['nationality'],
            'quote' => $this->quote ?? '',
            'photo' => media_url($this->getMediaUrl('photo')),
            'initials' => collect(preg_split('/\s+/', $name))
                ->filter()
                ->map(fn ($w) => mb_substr($w, 0, 1))
                ->take(2)
                ->implode(''),
        ];
    }

    /**
     * Split the existing verbatim role string for display without changing its stored value.
     * The PDF has a few missing labels, so known source institutions/nationalities are used
     * only as fallbacks when those labels are absent.
     *
     * @return array{course: string, university: string, nationality: string}
     */
    private function parseRoleMetadata(): array
    {
        $role = trim(preg_replace('/\s+/u', ' ', (string) $this->role) ?? '');
        $course = '';
        $university = '';
        $nationality = '';
        $nationalityOffset = null;
        $courseStart = null;

        if ($role === '') {
            return compact('course', 'university', 'nationality');
        }

        if (preg_match('/\bCourse:\s*(.*?)(?=\s+University:|\s+Nationality\b|$)/iu', $role, $matches, PREG_OFFSET_CAPTURE)) {
            $course = trim($matches[1][0]);
            $courseStart = $matches[1][1];
        }

        if (preg_match('/\bUniversity:\s*(.*?)(?=\s+Nationality\b|$)/iu', $role, $matches, PREG_OFFSET_CAPTURE)) {
            $university = trim($matches[1][0]);
        }

        if (preg_match('/\bNationality\s*:?\s*(.*?)$/iu', $role, $matches, PREG_OFFSET_CAPTURE)) {
            $nationality = trim($matches[1][0]);
            $nationalityOffset = $matches[0][1];
        } else {
            foreach (self::NATIONALITIES as $candidate) {
                $suffix = substr($role, -strlen($candidate));

                if (strcasecmp($suffix, $candidate) === 0) {
                    $nationality = $suffix;
                    $nationalityOffset = strlen($role) - strlen($candidate);
                    break;
                }
            }
        }

        if ($university === '') {
            $institutions = self::INSTITUTIONS;
            usort($institutions, static fn (string $left, string $right): int => strlen($right) <=> strlen($left));

            $institutionStart = null;
            foreach ($institutions as $institution) {
                $position = stripos($role, $institution);

                if ($position !== false && ($institutionStart === null || $position < $institutionStart)) {
                    $institutionStart = $position;
                }
            }

            if ($institutionStart !== null) {
                $universityEnd = $nationalityOffset ?? strlen($role);
                $university = trim(substr($role, $institutionStart, $universityEnd - $institutionStart));

                if ($courseStart !== null) {
                    $course = trim(substr($role, $courseStart, $institutionStart - $courseStart));
                } else {
                    $course = trim(substr($role, 0, $institutionStart));
                }
            }
        }

        return compact('course', 'university', 'nationality');
    }
}

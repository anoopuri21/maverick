<?php

namespace Tests\Unit;

use App\Models\StudentSuccessStory;
use Tests\TestCase;

class StudentSuccessStoryMetadataTest extends TestCase
{
    public function test_pdf_role_metadata_is_split_for_card_display_without_mutating_source_text(): void
    {
        $cases = [
            [
                'Course: MBA University of Gloucestershire Nationality: Philippine',
                'MBA',
                'University of Gloucestershire',
                'Philippine',
            ],
            [
                'MSc Counselling Psychology Girne American University Syria',
                'MSc Counselling Psychology',
                'Girne American University',
                'Syria',
            ],
            [
                'Course: Bachelor’s in International Business Management University: Girne American University Nationality Bangladesh',
                'Bachelor’s in International Business Management',
                'Girne American University',
                'Bangladesh',
            ],
            [
                'Dual Phd , Ph.D. in Forensic Accounting and Audit & DBA in Finance Charisma University & Rushford Business School Jordan',
                'Dual Phd , Ph.D. in Forensic Accounting and Audit & DBA in Finance',
                'Charisma University & Rushford Business School',
                'Jordan',
            ],
            [
                'Course: BBA in Business Administration| University: Rushford Business School Nationality: Myanmar',
                'BBA in Business Administration|',
                'Rushford Business School',
                'Myanmar',
            ],
        ];

        foreach ($cases as [$sourceRole, $course, $university, $nationality]) {
            $story = new StudentSuccessStory;
            $story->forceFill([
                'name' => 'PDF student',
                'role' => $sourceRole,
            ]);

            $payload = $story->cardPayload();

            $this->assertSame($sourceRole, $payload['role']);
            $this->assertSame($course, $payload['course']);
            $this->assertSame($university, $payload['university']);
            $this->assertSame($nationality, $payload['nationality']);
        }
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\FacultyInsight;
use App\Models\PartnerLogo;
use App\Models\Program;
use App\Models\Testimonial;
use App\Models\UniversityPartner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Support\PublicContentCache;

class SiteOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $counts = PublicContentCache::remember(PublicContentCache::ADMIN_OVERVIEW, function () {
            return [
                'programs' => Program::where('is_active', true)->count(),
                'partners' => UniversityPartner::where('is_active', true)->count(),
                'testimonials' => Testimonial::where('is_active', true)->count(),
                'events' => Event::where('is_active', true)->where('event_date', '>=', now())->count(),
                'faculty' => FacultyInsight::where('is_active', true)->count(),
                'logos' => PartnerLogo::where('is_active', true)->count(),
            ];
        });

        return [
            Stat::make('Total Programs', $counts['programs'])
                ->description('Active programs on the site')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->url('/admin/programs'),

            Stat::make('University Partners', $counts['partners'])
                ->description('Global partner institutions')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('warning')
                ->url('/admin/university-partners'),

            Stat::make('Testimonials', $counts['testimonials'])
                ->description('Video testimonials')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('info')
                ->url('/admin/testimonials'),

            Stat::make('Upcoming Events', $counts['events'])
                ->description('Scheduled events')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->url('/admin/events'),

            Stat::make('Faculty Insights', $counts['faculty'])
                ->description('Published articles')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success')
                ->url('/admin/faculty-insights'),

            Stat::make('Partner Logos', $counts['logos'])
                ->description('Alumni + Accreditation logos')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info')
                ->url('/admin/partner-logos'),
        ];
    }
}

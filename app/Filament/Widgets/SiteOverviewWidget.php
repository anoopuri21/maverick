<?php

namespace App\Filament\Widgets;

use App\Models\Program;
use App\Models\Testimonial;
use App\Models\Event;
use App\Models\FacultyInsight;
use App\Models\UniversityPartner;
use App\Models\PartnerLogo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Programs', Program::where('is_active', true)->count())
                ->description('Active programs on the site')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->url('/admin/programs'),

            Stat::make('University Partners', UniversityPartner::where('is_active', true)->count())
                ->description('Global partner institutions')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('warning')
                ->url('/admin/university-partners'),

            Stat::make('Testimonials', Testimonial::where('is_active', true)->count())
                ->description('Video testimonials')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('info')
                ->url('/admin/testimonials'),

            Stat::make('Upcoming Events', Event::where('is_active', true)->where('event_date', '>=', now())->count())
                ->description('Scheduled events')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->url('/admin/events'),

            Stat::make('Faculty Insights', FacultyInsight::where('is_active', true)->count())
                ->description('Published articles')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success')
                ->url('/admin/faculty-insights'),

            Stat::make('Partner Logos', PartnerLogo::where('is_active', true)->count())
                ->description('Alumni + Accreditation logos')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info')
                ->url('/admin/partner-logos'),
        ];
    }
}
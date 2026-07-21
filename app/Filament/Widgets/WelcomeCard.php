<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeCard extends Widget
{
    protected static string $view = 'filament.widgets.welcome-card';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';
}
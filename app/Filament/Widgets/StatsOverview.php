<?php

namespace App\Filament\Widgets;

use App\Models\Chall;
use App\Models\User;
use App\Models\Category;
use App\Models\Submisions;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    //protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Challenges', Chall::count())
                ->description('Total challenges available')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('info'),
            
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Total Categories', Category::count())
                ->description('Challenge categories')
                ->descriptionIcon('heroicon-m-folder')
                ->color('warning'),
            
            Stat::make('Total Submissions', Submisions::count())
                ->description('Flag submissions')
                ->descriptionIcon('heroicon-m-flag')
                ->color('danger'),
        ];
    }
}

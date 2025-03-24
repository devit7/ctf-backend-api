<?php

namespace App\Filament\Widgets;

use App\Models\Submisions;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SubmissionChart extends ChartWidget
{
    protected static ?string $heading = 'Submission Statistics';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get submission statistics
        $correct = Submisions::where('status', 'correct')->count();
        $incorrect = Submisions::where('status', 'incorrect')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Submissions',
                    'data' => [$correct, $incorrect],
                    'backgroundColor' => ['#10B981', '#EF4444'], // green for correct, red for incorrect
                ],
            ],
            'labels' => ['Correct', 'Incorrect'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}

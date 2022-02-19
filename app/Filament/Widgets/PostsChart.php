<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\Widget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PostsChart extends LineChartWidget
{
    protected int | string | array $columnSpan = 'full';

    
    protected function getHeading(): string
    {
        return 'Blog posts';
    }

    protected function getData(): array
    {
        $dataCreated = Trend::model(Post::class)
            ->between(
                start: now()->subMonths(11),
                end: now()
            )
            ->perMonth()
            ->count();

        $dataPublished = Trend::query(Post::published())
            ->between(
                start: now()->subMonths(11),
                end: now()
            )
            ->perMonth()
            ->count();
        
        return [
            'datasets' => [
                [
                    'label' => 'Published',
                    'data' => $dataPublished->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#22c55e'
                ],
                [
                    'label' => 'Created',
                    'data' => $dataCreated->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#0ea5e9'
                ],
            ],
            'labels' => $dataCreated->map(fn (TrendValue $value) => $value->date),
        ];
    }
}

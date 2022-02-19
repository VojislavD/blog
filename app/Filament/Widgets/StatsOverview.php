<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Collection;

class StatsOverview extends StatsOverviewWidget
{
    protected function  getCards(): array
    {
        $last7days = $this->postsLast7days();
        $difference = $this->postsDifferece($last7days);

        return [
            Card::make('Posts Last 7 Days', $last7days)
                ->description($this->postsDiffereceText($difference))
                ->descriptionIcon($this->postsDiffereceIcon($difference))
                ->color($this->postsDiffereceColor($difference))
        ];
    }

    private function postsLast7days(): int
    {
        return Post::where('created_at', '>', today()->subDays(7))->count();
    }

    private function postsDiffereceText(int $difference): string
    {   
        if ($difference > 0) {
            return $difference.' increase';            
        } else if ($difference < 0) {
            return $difference.' decrease';
        } else {
            return 'Same as before';
        }
    }

    private function postsDiffereceIcon(int $difference): string
    {
        if ($difference > 0) {
            return 'heroicon-s-trending-up';            
        } else if ($difference < 0) {
            return 'heroicon-s-trending-down';
        } else {
            return '';
        }
    }

    private function postsDiffereceColor(int $difference): string
    {
        if ($difference > 0) {
            return 'success';            
        } else if ($difference < 0) {
            return 'danger';
        } else {
            return 'primary';
        }
    }

    private function postsDifferece(int $last7days): int
    {
        $last7to14days = Post::where('created_at', '>', today()->subDays(7))
            ->where('created_at', '<', today()->subDays(14))
            ->count();
        
        return $last7days - $last7to14days;
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Collection;

class StatsOverview extends StatsOverviewWidget
{
    protected function  getCards(): array
    {
        $postsLast7Days = $this->postsLast7Days();
        $postsDifference = $this->postsDifferece($postsLast7Days);
        
        $commentsLast7Days = $this->commentsLast7Days();
        $commentsDifference = $this->commentsDifferece($commentsLast7Days);

        return [
            Card::make('Posts Last 7 Days', $postsLast7Days)
                ->description($this->differeceText($postsDifference))
                ->descriptionIcon($this->differeceIcon($postsDifference))
                ->color($this->differeceColor($postsDifference)),
            Card::make('Comments Last 7 Days', $commentsLast7Days)
                ->description($this->differeceText($commentsDifference))
                ->descriptionIcon($this->differeceIcon($commentsDifference))
                ->color($this->differeceColor($commentsDifference)),
        ];
    }

    private function postsLast7Days(): int
    {
        return Post::where('created_at', '>', today()->subDays(7))->count();
    }

    private function postsDifferece(int $last7days): int
    {
        $last7to14days = Post::where('created_at', '>', today()->subDays(7))
            ->where('created_at', '<', today()->subDays(14))
            ->count();
        
        return $last7days - $last7to14days;
    }

    private function commentsLast7Days(): int
    {
        return Comment::where('created_at', '>', today()->subDays(7))->count();
    }

    private function commentsDifferece(int $last7days): int
    {
        $last7to14days = Comment::where('created_at', '>', today()->subDays(7))
            ->where('created_at', '<', today()->subDays(14))
            ->count();
        
        return $last7days - $last7to14days;
    }

    private function differeceText(int $difference): string
    {   
        if ($difference > 0) {
            return $difference.' increase';            
        } else if ($difference < 0) {
            return $difference.' decrease';
        } else {
            return 'Same as before';
        }
    }

    private function differeceIcon(int $difference): string
    {
        if ($difference > 0) {
            return 'heroicon-s-trending-up';            
        } else if ($difference < 0) {
            return 'heroicon-s-trending-down';
        } else {
            return 'heroicon-s-minus-sm';
        }
    }

    private function differeceColor(int $difference): string
    {
        if ($difference > 0) {
            return 'success';            
        } else if ($difference < 0) {
            return 'danger';
        } else {
            return 'primary';
        }
    }
}

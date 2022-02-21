<?php

namespace App\Enums;

enum CommentStatus: int 
{
    use InvokableClass;
    
    case Pending = 1;
    case Approved = 2;
    case Rejected = 3;

    public function name(): string
    {
        return match($this)
        {
            CommentStatus::Pending => __("Pending"),
            CommentStatus::Approved => __("Approved"),
            CommentStatus::Rejected => __("Rejected"),
            default => __('N/A')
        };
    }
}
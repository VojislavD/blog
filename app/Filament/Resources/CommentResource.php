<?php

namespace App\Filament\Resources;

use App\Enums\CommentStatus;
use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-annotation';

    protected static ?string $navigationGroup = 'App';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->required()->email(),
                Forms\Components\Textarea::make('body')->required()->rules('max:500'),
                Forms\Components\Select::make('status')
                    ->options([
                        CommentStatus::Pending->value =>  CommentStatus::Pending->name,
                        CommentStatus::Approved->value =>  CommentStatus::Approved->name,
                        CommentStatus::Rejected->value =>  CommentStatus::Rejected->name,
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.title'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->enum([
                        CommentStatus::Pending->value => CommentStatus::Pending->name,
                        CommentStatus::Approved->value => CommentStatus::Approved->name,
                        CommentStatus::Rejected->value => CommentStatus::Rejected->name
                    ])
                    ->colors([
                        'warning' => CommentStatus::Pending->value,
                        'success' => CommentStatus::Approved->value,
                        'danger' => CommentStatus::Rejected->value
                    ])
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        CommentStatus::Pending->value => CommentStatus::Pending->name,
                        CommentStatus::Approved->value => CommentStatus::Approved->name,
                        CommentStatus::Rejected->value => CommentStatus::Rejected->name
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            // 'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}

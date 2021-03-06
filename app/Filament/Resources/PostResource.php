<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'App';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\BelongsToSelect::make('category_id')->relationship('category', 'name')->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->unique()
                    ->reactive()
                    ->afterStateUpdated(fn ($set, $state) => $set('slug', str($state)->slug())),
                Forms\Components\TextInput::make('slug')->required()->disabled(),
                Forms\Components\RichEditor::make('body')->required()->rules('min:120'),
                Forms\Components\FileUpload::make('featured_image')
                    ->label('Featured Image')
                    ->image()
                    ->maxSize(1024)
                    ->imagePreviewHeight(250)
                    ->nullable()
                    ->disk('s3'),
                Forms\Components\Toggle::make('published')->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('author'),
                Tables\Columns\BooleanColumn::make('published'),
                Tables\Columns\TextColumn::make('published_at')
            ])
            ->filters([
                Tables\Filters\Filter::make('published')
                    ->query(fn (Builder $query): Builder => $query->where('published', true))
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

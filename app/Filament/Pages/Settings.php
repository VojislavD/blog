<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Account';
    
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.settings';

    public $name;

    public $email;

    public $avatar;
    
    public $current_password;

    public $new_password;

    public $new_password_confirmation;

    public function mount()
    {
        $this->form->fill([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'avatar' => auth()->user()->avatar
        ]);
    }

    public function submit()
    {
        $this->form->getState();

        $state = array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->new_password ? Hash::make($this->new_password) : null,
            'avatar' => $this->avatar ? array_values($this->avatar)[0] : null
        ]);

        auth()->user()->update($state);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->notify('success', 'Your profile has been updated.');
    }

    public function getCancelButtonUrlProperty(): string
    {
        return static::getUrl();
    }

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Profile'
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('General')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->required(),
                    FileUpload::make('avatar')
                        ->label('Avatar')
                        ->image()
                        ->maxSize(1024)
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetWidth('50')
                        ->imageResizeTargetHeight('50')
                        ->disk('s3')
                ]),
            Section::make('Update Password')
                ->columns(2)
                ->schema([
                    TextInput::make('current_password')
                        ->label('Current Password')
                        ->password()
                        ->rules(['required_with:new_password'])
                        ->currentPassword()
                        ->autocomplete('off')
                        ->columnSpan(1),
                    Grid::make()
                        ->schema([
                            TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->rules(['confirmed'])
                                ->autocomplete('new-password'),
                            TextInput::make('new_password_confirmation')
                                ->label('Confirm Password')
                                ->password()
                                ->rules([
                                    'required_with:new_password',
                                ])
                                ->autocomplete('new-password'),
                        ]),
                ]),
        ];
    }
}

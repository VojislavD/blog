<?php

use App\Filament\Pages\Settings;

use function Pest\Livewire\livewire;

it('can render settings page', function () {
    $this->get(Settings::getUrl())->assertSuccessful();
});

it('can retrieve data on settings page', function () {
    livewire(Settings::class)
        ->assertSet('name', auth()->user()->name)
        ->assertSet('email', auth()->user()->email);
});

it('can validate input', function () {
    $data = [
        'name' => '',
        'email' => '',
    ];
    
    livewire(Settings::class)
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->call('submit')
        ->assertHasErrors([
            'name' => 'required',
            'email' => 'required'
        ]);

    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
    ];
    
    livewire(Settings::class)
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->call('submit')
        ->assertHasNoErrors();
});

it('can update', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
    ];
    
    livewire(Settings::class)
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'id' => auth()->id(),
        'name' => $data['name'],
        'email' => $data['email']
    ]);
});
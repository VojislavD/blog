<?php

use App\Filament\Resources\CategoryResource;
use App\Models\Category;

use function Pest\Livewire\livewire;

it('can render list page', function () {
    $this->get(CategoryResource::getUrl())->assertSuccessful();
});

it('can render create page', function () {
    $this->get(CategoryResource::getUrl('create'))->assertSuccessful();
});

it('can render edit page', function () {
    $this->get(CategoryResource::getUrl('edit', [
        'record' => Category::factory()->create()
    ]))->assertSuccessful();
});

it('can display data on list page', function () {
    $category = Category::factory()->create();
 
    livewire(CategoryResource\Pages\ListCategories::class)
        ->assertSee([
            $category->name,
        ]);
});

it('can retrieve data on edit page', function () {
    $category = Category::factory()->create();
 
    livewire(CategoryResource\Pages\EditCategory::class, [
        'record' => $category->getKey(),
    ])
        ->assertSet('data.name', $category->name);
});

it('can validate input', function () {
    $data = [
        'name' => '',
    ];

    livewire(CategoryResource\Pages\CreateCategory::class)
        ->set('data.name', $data['name'])
        ->call('create')
        ->assertHasErrors([
            'data.name' => 'required',
        ]);

    $data = [
        'name' => 'This is name and it should not be more than 50 characters',
    ];

    livewire(CategoryResource\Pages\CreateCategory::class)
        ->set('data.name', $data['name'])
        ->call('create')
        ->assertHasErrors([
            'data.name' => 'max'
        ]);

    $data = [
        'name' => 'New Category',
    ];

    livewire(CategoryResource\Pages\CreateCategory::class)
        ->set('data.name', $data['name'])
        ->call('create')
        ->assertHasNoErrors();
});

it('can update', function () {
    $category = Category::factory()->create();

    $newData = [
        'name' => 'New Category',
    ];

    livewire(CategoryResource\Pages\EditCategory::class, [
        'record' => $category->getKey(),
    ])
        ->set('data.name', $newData['name'])
        ->call('save');

    expect($category->refresh())
        ->name->toBe($newData['name']);
});

it('can create', function () {
    $data = [
        'name' => 'New Category',
    ];

    livewire(CategoryResource\Pages\CreateCategory::class)
        ->set('data.name', $data['name'])
        ->call('create');
 
    $this->assertDatabaseHas(Category::class, [
        'name' => $data['name'],
    ]);
});

it('can delete', function () {
    $category = Category::factory()->create();
 
    livewire(CategoryResource\Pages\EditCategory::class, [
        'record' => $category->getKey(),
    ])
        ->call('delete');
    
    $this->assertDatabaseMissing('categories', [
        'id' => $category->id
    ]);
});
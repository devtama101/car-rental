<?php

namespace App\Filament\Customer\Pages;

use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class MyProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.customer.pages.my-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->person?->phone,
            'address' => $user->person?->address,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique('users', 'email', ignorable: auth()->user()),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel()
                    ->maxLength(20),
                Textarea::make('address')
                    ->label(__('Address'))
                    ->maxLength(500)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $person = $user->person;
        if ($person) {
            $person->update([
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]);
        } else {
            $user->person()->create([
                'type' => 'customer',
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]);
        }

        Notification::make()
            ->title(__('Profile updated successfully'))
            ->success()
            ->send();
    }
}

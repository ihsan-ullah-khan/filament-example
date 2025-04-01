<?php

namespace App\Filament\Clusters\Profile\Pages;

use App\Filament\Clusters\Profile;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class Change_Password extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Change Password';

//    protected static ?string $navigationGroup = 'Profile Settings';

    protected static string $view = 'filament.pages.change_-password';

    protected static ?string $cluster = Profile::class;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            auth()->user()->attributesToArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Current Password')
                    ->password()
                    ->autofocus()
                    ->revealable()
                    ->required(),

                TextInput::make('password')
                    ->label('New Password')
                    ->password()
                    ->autofocus()
                    ->revealable()
                    ->required(),

                TextInput::make('password_confirmation')
                    ->label('Confirm New Password')
                    ->password()
                    ->revealable()
                    ->same('password')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('update')
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update()
    {
        $data = $this->form->getState();

        // Validate Current Password
        if (!Hash::check($data['current_password'], auth()->user()->password)){
            Notification::make()
                ->title('Current password is incorrect.')
                ->danger()
                ->send();
            return;
        }
        auth()->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        Notification::make()
            ->title('Password Updated!')
            ->success()
            ->send();
    }
}

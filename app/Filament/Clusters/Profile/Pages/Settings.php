<?php

namespace App\Filament\Clusters\Profile\Pages;

use App\Filament\Clusters\Profile;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigation = 'Profile';

    protected static ?string $navigationLabel = 'Profile';

//    protected static ?string $navigationGroup = 'Profile Settings';

    protected static ?string $cluster = Profile::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';


    public function mount(): void
    {
        $this->data = auth()->user()->attributesToArray();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profile Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->default($this->data['name'] ?? ''),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->default($this->data['email'] ?? ''),
                    ]),

                Section::make('Change Password')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Current Password')
                            ->columnSpan(2)
                            ->currentPassword()
                            ->password()
                            ->revealable()
                            ->required(),

                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->required(),

                        TextInput::make('password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->required(),
                    ])
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    public function getFormActions(): array
    {
        return [
            Action::make('updateProfile')
                ->color('primary')
                ->label('Update Profile')
                ->submit('updateProfile'),

            Action::make('updatePassword')
                ->color('secondary')
                ->label('Update Password')
                ->submit('updatePassword'),
        ];
    }

    public function updateProfile()
    {
        $profileData = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
        ];

        auth()->user()->update($profileData);

        Notification::make()
            ->title('Profile Updated!')
            ->success()
            ->send();
    }

    public function updatePassword()
    {
        $data = $this->data;

        // Validate current password
        if (!Hash::check($data['current_password'], auth()->user()->password)) {
            Notification::make()
                ->title('Current password is incorrect.')
                ->danger()
                ->send();
            return;
        }

        // Update the password
        auth()->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        Notification::make()
            ->title('Password Updated!')
            ->success()
            ->send();
    }
}

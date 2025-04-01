<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'fas-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Name
                Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable(),

                //  User
                Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->sortable()
                ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];

    }

    public static function sidebar(User $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle('User Menu')
            ->setNavigationItems([
//                PageNavigationItem::make('User Dashboard')
//                    ->url(function () use ($record) {
//                        return static::getUrl('dashboard', ['record' => $record->id]);
//                    }),
                PageNavigationItem::make('View User')
                    ->url(function () use ($record) {
                        return static::getUrl('view', ['record' => $record->id]);
                    }),
                PageNavigationItem::make('Edit User')
                    ->url(function () use ($record) {
                        return static::getUrl('edit', ['record' => $record->id]);
                    }),
//                PageNavigationItem::make('Manage User')
//                    ->url(function () use ($record) {
//                        return static::getUrl('manage', ['record' => $record->id]);
//                    }),
                PageNavigationItem::make('Change Password')
                    ->url(function () use ($record) {
                        return static::getUrl('password.change', ['record' => $record->id]);
                    }),

                // ... more items
            ]);
    }

    // Old code
//    public static function getPages(): array
//    {
//        return [
//            'index' => Pages\ListUsers::route('/'),
//            'create' => Pages\CreateUser::route('/create'),
//            'edit' => Pages\EditUser::route('/{record}/edit'),
//            'profile' => Pages\UserProfile::route('/profile'),
//            'change-password' => Pages\ChangePassword::route('/change-password'),
//        ];
//    }

// new
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}/view'),
            'password.change' => Pages\ChangePassword::route('/{record}/password/change'),


//            'view' => App\Filament\Resources\UserResource\Pages\ViewUser::route('/{record}/view'),
//            'manage' => App\Filament\Resources\UserResource\Pages\ManageUser::route('/{record}/manage'),
//            'password.change' => App\Filament\Resources\UserResource\Pages\ChangePasswordUser::route('/{record}/password/change'),
//            'dashboard' => App\Filament\Resources\UserResource\Pages\DashboardUser::route('/{record}/dashboard'),
            // ... more pages
        ];
    }



}

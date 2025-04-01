<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Address;
use App\Filament\Resources\CityResource\RelationManagers\CitiesRelationManager;
use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Filament\Resources\StateRsource\Pages\ManageStates;
use App\Models\State;
use App\Models\Country;
use App\Models\Upload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PHPUnit\Metadata\Group;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = Address::class;

    protected static SubNavigationPosition $subNavigationPosition = subNavigationPosition::Start;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Info')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\FileUpload::make('image')
                                        ->disk('public')
                                        ->columnSpan('full')
                                        ->directory('uploads/country/images')
                                        ->getUploadedFileNameForStorageUsing(function ($file): string {
                                            return (string)str($file->getClientOriginalName())
                                                ->prepend(now()->format('Y-m-d-His') . '-');
                                        })
                                        ->saveUploadedFileUsing(function ($file, $state, $set, $get) {

                                            // Save the uploaded file to the Upload model
                                            $originalPath = $file->store('uploads/country/images', 'public');
                                            $originalName = basename($originalPath);

                                            // Generate different sizes and paths (example using Intervention Image package)
                                            $sizes = [
                                                '100x100' => 'uploads/country/images/' . now()->format('Y-m-d-His') . '-100x100-' . $originalName,
                                                '300x300' => 'uploads/country/images/' . now()->format('Y-m-d-His') . '-300x300-' . $originalName,
                                                '600x600' => 'uploads/country/images/' . now()->format('Y-m-d-His') . '-600x600-' . $originalName,
                                            ];

                                            $upload = Upload::create([
                                                'original' => $originalPath,
                                                'name' => $originalName,
                                                'type' => 'image',
                                                'paths' => json_encode($sizes),
                                            ]);

                                            $set('upload_id', $upload->id);

                                            return $upload->id;
                                            // Set the avatar_id in the User model
                                            //$form->model()->upload_id = $upload->id;
                                        })
                                        ->reactive(),

                                    Forms\Components\Hidden::make('upload_id')
                                        ->default(fn($get) => $get('upload_id')),
                                ]),
                        ]),
                    Forms\Components\Wizard\Step::make('Details')
                        ->icon('fas-info')
                        ->description('State Info')
                        ->schema([
                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Name')
                                        ->placeholder('Write State\'s Name'),

                                    Forms\Components\Select::make('country_id')
                                        ->label('Country')
                                        ->options(Country::query()->pluck('name', 'id'))
                                        ->required(),
                                ]),

                        ]),
                    Forms\Components\Wizard\Step::make('Cities')
                        ->icon('heroicon-o-plus-circle')
                        ->description('State Cities')
                        ->schema([
                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\Repeater::make('cities')
                                        ->relationship()
                                        ->columnSpan('full')
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->label('City Name')
                                        ]),
                                ]),

                        ]),
                ])->columnSpan('full'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
            ])->columns(1)
            ->inlineLabel();
    }

    public static function getRelations(): array
    {
        return [
            CitiesRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name', 'country.name'
        ];
    }

//    public static function getRecordSubNavigation(Page $page): array
//    {
//        return $page->generateNavigationItems([
//            Pages\ViewState::class,
//            Pages\EditState::class,
//            Pages\ManageCities::class,
//            Pages\CreateState::class,
//        ]);
//    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStates::route('/'),
        ];
    }
}

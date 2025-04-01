<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Address;
use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use App\Models\Upload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = Address::class;

    protected static SubNavigationPosition $subNavigationPosition = subNavigationPosition::Start;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Persona')
                        ->schema([
                            Forms\Components\TextInput::make('name')->required(),
                            Forms\Components\TextInput::make('capital'),
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
                        ])->columns(2),

                        Forms\Components\Section::make('Description')
                        ->schema([
                           Forms\Components\Textarea::make('description')
                           ->label('Description')
                           ->rows(5),
                        ]),

                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                        ->schema([
                            Forms\Components\Toggle::make('is_visible')
                            ->label('Visible')
                            ->helperText('The country is only visible in that continent')
                            ->default(true),

                            Forms\Components\DatePicker::make('created_at')
                            ->label('Created At')
                            ->default(now())
                            ->required(),
                        ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\ImageColumn::make('image.original')
                    ->label('Country Image')
                    ->disk('public')
                    ->square(),

//                Tables\Columns\ImageColumn::make('image.original')
//                    ->label('Profile Image')
//                    ->disk('public'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->label(''),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
          'name'
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditCountry::class,
            Pages\ViewCountry::class,
            Pages\ListCountries::class
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'view' => Pages\ViewCountry::route('/{record}'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}

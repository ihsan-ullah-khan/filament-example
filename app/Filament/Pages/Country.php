<?php

namespace App\Filament\Pages;

use App\Filament\Resources\StateResource;
use App\Filament\Widgets\StatsAdminOverview;
use App\Filament\Widgets\StatsOverview;
use App\Models\State;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Country extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.country';
    protected static ?string $navigationLabel = 'List of States';
    protected static ?string $navigationGroup = 'Country';

    protected ?string $heading ='States';


    protected function getHeaderWidgets(): array
    {
        return [
        StatsAdminOverview::class,
        ];

    }


    public function table(Table $table): Table
    {
        return $table
            ->query(State::query())
            ->defaultSort('created-at', 'desc')
            ->columns([
                TextColumn::make('name'),
            ]);
    }
}

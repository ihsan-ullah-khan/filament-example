<?php

namespace App\Filament\Pages;

use App\Models\City;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class UpdateCity extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.update-city';

    protected static ?string $navigationLabel = 'Update Cities';

    protected static ?string $navigationGroup = 'Country';

    public $cityIds = [1,2,3];

    public $cities = [];

    public function mount()
    {
        $this->loadCities($this->cityIds);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('cities')
                    ->addable(false)
                    ->schema([
                        TextInput::make('name')
                            ->label('Country Name')
                            ->required(),
                    ])->columnSpan(1)
                    ->hidden(fn () => empty($this->cities)),
            ])->columns(2);
    }

    public function loadCities($cityIds)
    {
        $this->cities = City::whereIn('id', $this->cityIds)->get()->toArray();
        $this->form->fill(['cities' => $this->cities]);
    }

    public function submit()
    {
        foreach ($this->cities as $cityData)
        {
            $city = City::find($cityData['id']);

            if ($city)
            {
                $city->update($cityData);
            }
        }

        Notification::make()
            ->title('Cities Updated Successfully')
            ->success()
            ->send();
    }
}

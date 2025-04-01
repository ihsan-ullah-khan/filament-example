<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class UpdateCountries extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.update-countries';

    protected static ?string $navigationLabel = 'Update Countries';

    protected static ?string $navigationGroup = 'Country';

    public $countryIds = [1, 2];
    public $countries = [];

    public function mount()
    {
        $this->loadCountries($this->countryIds);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('countries')
                    ->schema([
                        TextInput::make('id')
                            ->label('ID')
                            ->disabled(),
                        TextInput::make('name')
                            ->label('Country Name')
                            ->required(),
                        // Add more fields as necessary
                    ])
                    ->columns(2)
                    ->hidden(fn () => empty($this->countries)),
            ]);
    }

    public function loadCountries($countryIds)
    {
        $this->countries = \App\Models\Country::whereIn('id', $countryIds)->get()->toArray();
        $this->form->fill(['countries' => $this->countries]);
    }

    public function submit()
    {
        foreach ($this->countries as $countryData) {
            $country = \App\Models\Country::find($countryData['id']);
            if ($country) {
                $country->update($countryData);
            }
        }

        Notification::make()
            ->title('Countries Updated Successfully')
            ->success()
            ->send();
    }
}

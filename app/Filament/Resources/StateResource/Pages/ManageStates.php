<?php

namespace App\Filament\Resources\StateRsource\Pages;

use App\Filament\Resources\StateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStates extends ManageRecords
{
    protected static string $resource = StateResource::class;

    protected function getActions():array{
        return [
            CreateAction::make()
            ->createAnother(false),
        ];
    }
}


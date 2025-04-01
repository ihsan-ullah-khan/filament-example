<?php

use App\Filament\Clusters\Profile\Pages\Settings;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function (){
    Route::post('/custom-page/update-user-information', [Settings::class, 'updateUserInformation'])->name('filament.pages.custom-page.update-user-information');
    Route::post('/custom-page/update-password', [Settings::class, 'updatePassword'])->name('filament.pages.custom-page.update-password');
});




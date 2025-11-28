<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use App\Filament\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Settings extends EditRecord
{
    protected static string $resource = ProductsResource::class;
}

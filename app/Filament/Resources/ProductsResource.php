<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use App\Models\Products;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;

class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Product Name')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description'),

            TextInput::make('price')
                ->label('Price')
                ->numeric()
                ->required(),

            TextInput::make('stock')
                ->label('Stock')
                ->numeric()
                ->default(0),

            TextInput::make('quantity')
                ->label('Quantity')
                ->numeric()
                ->default(1),

            Select::make('category')
    ->options([
        'liquid' => 'Liquid',
        'pod_system' => 'Pod System',
        'mod_system' => 'Mod System',
        'atomizer' => 'Atomizer',
    ])
    ->required()
    ->reactive() // supaya bisa trigger event
    ->afterStateUpdated(function ($state, callable $set) {
        $defaultAttributes = [];

        if ($state === 'liquid') {
            $defaultAttributes = [
                ['name' => 'Nic Level', 'value' => '3mg'],
            ];
        } elseif ($state === 'pod_system' || $state === 'mod_system' || $state === 'atomizer') {
            $defaultAttributes = [
                ['name' => 'Warna', 'value' => 'Hitam'],
            ];
        }

        $set('attributes', $defaultAttributes);
    }),

            FileUpload::make('image')
                ->label('Product Image')
                ->disk('public')
                ->directory('products')
                ->image()
                ->imagePreviewHeight('150')
                ->maxSize(2048),

            Repeater::make('attributes')
                ->label('Product Attributes')
                ->schema([
                    TextInput::make('name')
                        ->label('Attribute Name')
                        ->required(),
                    TextInput::make('value')
                        ->label('Attribute Value')
                        ->required(),
                ])
                ->default([]) // awalnya kosong
                ->createItemButtonLabel('Tambah Atribut')
                ->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->size(60),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(fn () => Category::pluck('name', 'id')),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }
}

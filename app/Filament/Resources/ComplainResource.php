<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplainResource\Pages;
use App\Filament\Resources\ComplainResource\RelationManagers;
use App\Models\Classes;
use App\Models\Complain;
use App\Models\ComplaintFile;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Log;

class ComplainResource extends Resource
{
    protected static ?string $model = Complain::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Wizard::make([
                Wizard\Step::make('Offender')
                    ->schema([
                        Select::make('class_id')
                            ->label('Class')
                            ->options(Classes::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                            ->afterStateUpdated(function ($state, callable $set) {
                                $students = User::whereHas('hasStudent', function ($subquery) use ($state) {
                                    $subquery->where('class_id', $state);
                                })->pluck('name', 'id')->toArray();

                                $set('offender_name_options', $students);
                            }),
                        Select::make('culprit_id')
                            ->label('Name')
                            // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                            ->options(function (callable $get) {
                                $classId = $get('class_id');
                                if ($classId) {
                                    return User::whereHas('hasStudent', function ($subquery) use ($classId) {
                                        $subquery->where('class_id', $classId);
                                    })->pluck('name', 'id')->toArray();
                                }
                                return [];
                            }),
                    ]),
                Wizard\Step::make('Fault')
                    ->schema([
                        Select::make('title')
                            ->label('Disciplinary Action')
                            ->options([
                                'merokok' => 'Merokok',
                                'berjudi' => 'Berjudi'
                            ])
                            // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                            ->required(),
                        TextInput::make('description')
                            ->label('Details')
                            ->autocomplete(false)
                            ->maxLength(255)
                            // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                            ->required(),
                        DateTimePicker::make('incident_date')
                            ->label('Date')
                            // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                            ->required(),
                        FileUpload::make('file_path')
                            ->label('Evidence')
                            ->disk('public')
                            ->directory('complain')
                            ->visibility('public')
                            ->imageEditor()
                            ->reorderable()
                            ->appendFiles()
                            ->multiple()
                            ->downloadable()
                            // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                            ->formatStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return collect($state)->map(function ($path) {
                                        return ('complain/' .$path);
                                    })->toArray();
                                }
                                return $state;
                            }),
                    ]),
                Wizard\Step::make('Witness')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pending' => 'Pending',
                                'Investigating' => 'Investigating',
                                'Resolved' => 'Resolved',
                                'Rejected' => 'Rejected'
                            ])
                            // ->visible(fn () => \Auth::user()->hasRole('super_admin'))
                            ->required(),
                        Repeater::make('Witness')
                            ->relationship('witness')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Name')
                                        // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                                        ->autocomplete(false),
                                    TextInput::make('phone')
                                        ->label('Phone')
                                        ->autocomplete(false)
                                        ->prefix('+60')
                                        // ->disabled(fn () => \Auth::user()->hasRole('super_admin'))
                                        ->tel(),
                                ])
                            // ->disableItemDeletion(fn () => \Auth::user()->hasRole('super_admin'))
                            // ->disableItemCreation(fn () => \Auth::user()->hasRole('super_admin'))
                            ->addActionLabel('Add More Witness'),
                    ])
            ])
            ->columnSpan('full')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('description'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'Pending' => 'gray',
                        'Investigating' => 'warning',
                        'Resolved' => 'success',
                        'Rejected' => 'danger'
                    }),
                // ImageColumn::make('complainFiles.file_path')
                // ->getStateUsing(function ($record) {
                //     // Get the first file in the collection
                //     $firstFile = $record->complainFiles->first();
                    
                //     // Normalize the file path and ensure slashes are correct
                //     if ($firstFile && isset($firstFile->file_path)) {
                //         $filePath = str_replace('\\', '/', $firstFile->file_path); // Normalize the slashes
                //         return url('storage/' . $filePath); // Construct the full URL
                //     }
            
                //     return null; // Return null if there's no file
                // }),
              
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplains::route('/'),
            'create' => Pages\CreateComplain::route('/create'),
            'edit' => Pages\EditComplain::route('/{record}/edit'),
        ];
    }
}

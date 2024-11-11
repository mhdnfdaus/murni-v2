<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Classes;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Crypt;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Personal')
                        ->schema([
                            Select::make('roles')
                                ->relationship('roles', 'name')
                                ->required(),
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('ic_no')
                                ->numeric()
                                ->length(12)
                                ->required(),
                            TextInput::make('phone')
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                            ->required()
                        ]),
                    Wizard\Step::make('Education')
                    ->schema([
                        Select::make('faculties')
                            ->options(Faculty::all()->pluck('name', 'id')->toArray())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                \Log::info('Selected faculty ID:', ['id' => $state]); // Debug line
                                $courses = Course::where('faculties_id', $state)->pluck('name', 'id')->toArray();
                                $set('courses', is_array($courses) ? $courses : []);
                                $set('classes', []);
                            }),
                        Select::make('courses')
                            ->options(function (callable $get) {
                                $courses = Course::where('faculties_id', $get('faculties'))->pluck('name', 'id')->toArray();
                                return is_array($courses) ? $courses : [];
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                \Log::info('Selected course ID:', ['id' => $state]);
                                $classes = Classes::where('course_id', $state)->pluck('name', 'id')->toArray();
                                $set('classes', is_array($classes) ? $classes : []);
                            }),
                        Select::make('classes')
                            ->options(function (callable $get) {
                                $classes = Classes::where('course_id', $get('courses'))->pluck('name', 'id')->toArray();
                                return is_array($classes) ? $classes : [];
                            })
                            ->required()
                    ]),
                    Wizard\Step::make('Private')
                        ->schema([
                            TextInput::make('email')
                                ->email()
                                ->required(),
                            TextInput::make('password')
                                ->password()
                                ->columnSpan('full')
                                ->required(),
                        ])
                    ])
                    ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('created_at')
                    ->dateTime(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

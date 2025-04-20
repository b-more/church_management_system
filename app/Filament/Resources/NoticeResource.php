<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoticeResource\Pages;
use App\Models\Notice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class NoticeResource extends Resource
{
    protected static ?string $model = Notice::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()),

                Forms\Components\Select::make('recipient_group')
                    ->label('To')
                    ->options([
                        'church' => 'Church',
                        'kingdom_workers' => 'Kingdom Workers',
                        'heads_of_department' => 'Heads of Department',
                        'executive_committee' => 'Executive Committee',
                        'building_committee' => 'Building Committee',
                        'media' => 'Media',
                        'hospitality' => 'Hospitality',
                        'ushering' => 'Ushering',
                        'pastoral_team' => 'Pastoral Team',
                        'all' => 'All Members',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('Subject/Title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('body')
                    ->label('Notice Content')
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Enter notice details (up to 1000 words)')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('notice-attachments'),

                Forms\Components\FileUpload::make('image_path')
                    ->label('Image (Optional)')
                    ->image()
                    ->disk('public')
                    ->directory('notice-images')
                    ->visibility('public')
                    ->imagePreviewHeight('250')
                    ->maxSize(5120) // 5MB limit
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('recipient_group')
                    ->label('To')
                    ->formatStateUsing(fn (string $state): string => Str::title(str_replace('_', ' ', $state)))
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Subject/Title')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('recipient_group')
                    ->label('Recipient Group')
                    ->options([
                        'church' => 'Church',
                        'kingdom_workers' => 'Kingdom Workers',
                        'heads_of_department' => 'Heads of Department',
                        'executive_committee' => 'Executive Committee',
                        'building_committee' => 'Building Committee',
                        'media' => 'Media',
                        'hospitality' => 'Hospitality',
                        'ushering' => 'Ushering',
                        'pastoral_team' => 'Pastoral Team',
                        'all' => 'All Members',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All notices')
                    ->trueLabel('Active notices')
                    ->falseLabel('Inactive notices'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNotices::route('/'),
            'create' => Pages\CreateNotice::route('/create'),
            'edit' => Pages\EditNotice::route('/{record}/edit'),
        ];
    }
}

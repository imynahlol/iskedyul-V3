<?php

namespace App\Filament\Pages;

use App\Models\SystemSetting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;

class SystemSettings extends Page {
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'System Settings';

    protected static ?string $title = 'System Settings';

    protected static string | \UnitEnum | null $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.pages.system-settings';

    public ?array $data = [];

    public function mount(): void {
        $settings = SystemSetting::all()->keyBy('key');

        $this->form->fill([
            'system_name' => $settings['system_name']->value ?? 'iSKEDYUL',
            'campus_name' => $settings['campus_name']->value ?? 'PUP Lopez Campus',
            'campus_address' => $settings['campus_address']->value ?? '',
            'system_description' => $settings['system_description']->value ?? '',
            'min_duration' => $settings['min_duration']->value ?? 1,
            'max_duration' => $settings['max_duration']->value ?? 4,
            'advance_days' => $settings['advance_days']->value ?? 30,
            'opening_time' => $settings['opening_time']->value ?? '07:00',
            'closing_time' => $settings['closing_time']->value ?? '21:00',
        ]);
    }

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                Section::make('General Information')
                    ->description('Manage the basic information displayed by iSKEDYUL.')
                    ->schema([
                        TextInput::make('system_name')
                            ->label('System Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('campus_name')
                            ->label('Campus Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('campus_address')
                            ->label('Campus Address')
                            ->maxLength(255),

                        Textarea::make('system_description')
                            ->label('System Description')
                            ->rows(3),
                    ])
                    ->columns(2),

                Section::make('Reservation Rules')
                    ->description('Configure the reservation duration and booking period.')
                    ->schema([
                        TextInput::make('min_duration')
                            ->label('Minimum Reservation Duration (Hours)')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TextInput::make('max_duration')
                            ->label('Maximum Reservation Duration (Hours)')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TextInput::make('advance_days')
                            ->label('Advance Booking Limit (Days)')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TimePicker::make('opening_time')
                            ->label('Reservation Opening Time')
                            ->required()
                            ->seconds(false),

                        TimePicker::make('closing_time')
                            ->label('Reservation Closing Time')
                            ->required()
                            ->seconds(false),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public function save(): void {
        $data = $this->form->getState();

        if ((int) $data['min_duration'] > (int) $data['max_duration']) {
            Notification::make()
                ->title('Invalid reservation duration')
                ->body('Minimum duration cannot exceed maximum duration.')
                ->danger()
                ->send();

            return;
        }

        if ($data['opening_time'] >= $data['closing_time']) {
            Notification::make()
                ->title('Invalid reservation hours')
                ->body('Opening time must be earlier than closing time.')
                ->danger()
                ->send();

            return;
        }

        $groups = [
            'system_name' => ['general', 'text'],
            'campus_name' => ['general', 'text'],
            'campus_address' => ['general', 'text'],
            'system_description' => ['general', 'textarea'],
            'min_duration' => ['reservation', 'number'],
            'max_duration' => ['reservation', 'number'],
            'advance_days' => ['reservation', 'number'],
            'opening_time' => ['reservation', 'time'],
            'closing_time' => ['reservation', 'time'],
        ];

        foreach ($groups as $key => [$group, $type]) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data[$key],
                    'group' => $group,
                    'type' => $type,
                ]
            );
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
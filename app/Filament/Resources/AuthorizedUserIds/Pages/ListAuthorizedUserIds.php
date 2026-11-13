<?php

namespace App\Filament\Resources\AuthorizedUserIds\Pages;

use App\Exports\AuthorizedUserIdsTemplateExport;
use App\Filament\Resources\AuthorizedUserIds\AuthorizedUserIdResource;
use App\Imports\AuthorizedUserIdsImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListAuthorizedUserIds extends ListRecords {
    protected static string $resource = AuthorizedUserIdResource::class;

    protected function getHeaderActions(): array {
        return [
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    return Excel::download(
                        new AuthorizedUserIdsTemplateExport(),
                        'iskedyul_authorized_ids_template.xlsx'
                    );
                }),

            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->schema([
                    FileUpload::make('file')
                        ->label('Excel / CSV File')
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv',
                            'application/vnd.ms-excel',
                        ])
                        ->disk('local')
                        ->directory('authorized-ids-imports')
                        ->visibility('private'),
                ])
                ->action(function (array $data) {
                    $import = new AuthorizedUserIdsImport();

                    try {
                        Excel::import($import, $data['file'], 'local');

                        Notification::make()
                            ->title('Import completed')
                            ->body(
                                "Imported: {$import->imported} | " .
                                "Skipped: {$import->skipped}"
                            )
                            ->success()
                            ->persistent()
                            ->send();

                        foreach ($import->errors as $error) {
                            Notification::make()
                                ->title('Skipped row')
                                ->body($error)
                                ->warning()
                                ->send();
                        }
                    } catch (\Throwable $exception) {
                        report($exception);

                        Notification::make()
                            ->title('Import failed')
                            ->body('The file could not be processed. Please check its format and contents.')
                            ->danger()
                            ->send();
                    }
                }),

            CreateAction::make(),
        ];
    }
}
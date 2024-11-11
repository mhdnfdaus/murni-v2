<?php

namespace App\Filament\Resources\ComplainResource\Pages;

use App\Filament\Resources\ComplainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditComplain extends EditRecord
{
    protected static string $resource = ComplainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Adding additional data for form fields
        $data['class_id'] = $this->record->culprit->hasStudent->class_id ?? null;
        $data['culprit_id'] = $this->record->culprit_id ?? null;
        $filePaths = $this->record->complainFiles->pluck('file_path')->toArray();
        
        // Process the paths to remove the 'complain/' prefix
        $data['file_path'] = array_map(function ($path) {
            return str_replace(['\\', 'complain/'], ['/', ''], $path);
        }, $filePaths);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle file removal
        $existingFiles = $this->record->complainFiles->pluck('file_path')->toArray();
        $newFiles = $data['file_path'] ?? [];

        // Files to delete
        $filesToDelete = array_diff($existingFiles, $newFiles);

        // Delete the removed files
        foreach ($filesToDelete as $file) {
            \Storage::disk('public')->delete($file);
            $this->record->complainFiles()->where('file_path', $file)->delete();
        }

        // Handle file upload
        if (isset($data['file_path']) && is_array($data['file_path'])) {
            foreach ($data['file_path'] as $index => $filePath) {
                $originalFileName = $data['original_file_name'][$index] ?? null;

                // Check if the file already exists, otherwise create it
                $this->record->complainFiles()->updateOrCreate(
                    ['file_path' => $filePath],
                    ['original_file_name' => $originalFileName]
                );
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Mutate the form data before saving
        $data = $this->mutateFormDataBeforeSave($data);

        // Update the record with the modified data
        $record->update($data);

        return $record;
    }
}

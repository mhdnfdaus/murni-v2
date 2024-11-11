<?php

namespace App\Filament\Resources\ComplainResource\Pages;

use App\Filament\Resources\ComplainResource;
use App\Models\Complain;
use App\Models\ComplaintFile;
use App\Models\Witness;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;

class CreateComplain extends CreateRecord
{
    protected static string $resource = ComplainResource::class;
    // Disable create & create another button in modal
    protected static bool $canCreateAnother = false;

    /**
     * Handle the record creation.
     *
     * @param array $data
     * @return Complain
     */
    protected function handleRecordCreation(array $data): Complain
    {
        return DB::transaction(function () use ($data) {

            $complain = Complain::create([
                'reporter_id' => Auth::id(),
                'culprit_id' => $data['culprit_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'],
                'incident_date' => $data['incident_date'],
            ]);

            if (isset($data['Witness']) && is_array($data['Witness'])) {
                foreach ($data['Witness'] as $witnessData) {
                    Witness::create([
                        'complain_id' => $complain->id,
                        'name' => $witnessData['name'],
                        'phone' => $witnessData['phone']
                    ]);
                }
            }

            if (isset($data['file_path']) && is_array($data['file_path'])) {
                foreach ($data['file_path'] as $index => $filePath) {

                    if ($filePath) {
                        ComplaintFile::create([
                            'complain_id' => $complain->id,
                            'file_path' => $filePath,
                        ]);
                    }
                }
            }

            return $complain;
        });
    }
}

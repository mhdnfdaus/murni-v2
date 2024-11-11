<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\StudentEdu;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function handleRecordCreation(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Extract personal and private information
            $personalInfo = [
                'name' => $data['name'],
                'ic_no' => $data['ic_no'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ];
            
            // Create the user
            $user = User::create($personalInfo);
            
            // Find the role by ID and assign it to the user
            $role = Role::findById($data['roles']);
            $user->assignRole($role);

            // Save education information
            StudentEdu::create([
                'user_id' => $user->id,
                'class_id' => $data['classes'],
            ]);

            return $user;
        });
    }
}

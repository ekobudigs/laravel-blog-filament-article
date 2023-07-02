<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;


    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['email_verified_at'] = Carbon::now();
    $data['password'] = bcrypt($data['password']);
    return $data;
}

protected function handleRecordCreation(array $data): Model
{
    $user = parent::handleRecordCreation($data);
    $user->assignRole('admin');

    return $user;

    
}
}

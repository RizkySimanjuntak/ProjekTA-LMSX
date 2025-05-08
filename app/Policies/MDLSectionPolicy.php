<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MDLSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class MDLSectionPolicy
{
    use HandlesAuthorization;

    public function update(User $user, MDLSection $section)
    {
        // Example authorization logic - adjust according to your needs
        return $user->id_role == 1 || // Admin
               ($user->id_role == 2 && $section->course->dosen_id == $user->id); // Dosen pemilik course
    }
}
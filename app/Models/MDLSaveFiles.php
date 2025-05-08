<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDLSaveFiles extends Model
{
    use HasFactory;
    protected $fillable = [

        'name',
        'description',

        'folder_id',
        'file_path',
        'created_at',
        'updated_at',
    ];

    protected $table = 'savefiles';

    // Relationship: A file belongs to a folder
    public function folder()
    {
        return $this->belongsTo(MDLFolder::class, 'folder_id');
}
}

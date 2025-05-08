<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDLFolder extends Model
{
    use HasFactory;

    protected $table = 'mdl_folder';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [ 'name','description', 'folder_path','learning_style_id','sub_topic_id'];





    public function sub_topic()
    {
        return $this->belongsTo(CourseSubtopik::class, 'sub_topic_id','id');
    }

    public function learning_style()
    {
        return $this->belongsTo(DimensionOption::class, 'learning_style_id','id');
    }
}

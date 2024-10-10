<?php

namespace App\Domain\Groups\Models;

use App\Domain\Classifiers\Models\ClassifierOption;
use App\Domain\Departments\Models\Department;
use App\Domain\Specialities\Models\Speciality;
use App\Domain\SubjectGroups\Models\SubjectGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $fillable = [
        'id',
        'name',
        'department_id',
        'speciality_id',
        'h_language',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class);
    }

    public function lang(): BelongsTo
    {
        return $this->belongsTo(ClassifierOption::class, 'h_language', 'id');
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(SubjectGroup::class,'group_subject_group','group_id','subject_group_id');
    }
}

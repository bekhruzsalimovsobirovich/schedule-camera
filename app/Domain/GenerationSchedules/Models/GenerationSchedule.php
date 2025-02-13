<?php

namespace App\Domain\GenerationSchedules\Models;

use App\Domain\Rooms\Models\Room;
use App\Domain\SubjectGroups\Models\SubjectGroup;
use App\Models\Traits\Filterable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenerationSchedule extends Model
{
    use Filterable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'teacher_id',
        'subject_group_id',
        'date',
        'pair',
    ];

    /**
     * @return BelongsTo
     */
    public function subjectGroup(): BelongsTo
    {
        return $this->belongsTo(SubjectGroup::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class,'teacher_id','id');
    }
}

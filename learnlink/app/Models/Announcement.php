<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Announcement extends Model { protected $fillable=['course_class_id','lecturer_id','title','body']; public function courseClass(): BelongsTo { return $this->belongsTo(CourseClass::class); } public function lecturer(): BelongsTo { return $this->belongsTo(User::class,'lecturer_id'); } }

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; use Illuminate\Database\Eloquent\Relations\BelongsToMany; use Illuminate\Database\Eloquent\Relations\HasMany;
class CourseClass extends Model { protected $fillable=['course_title','course_code','department','academic_year','semester','is_archived']; protected function casts(): array { return ['is_archived'=>'boolean']; } public function lecturer(): BelongsTo { return $this->belongsTo(User::class,'lecturer_id'); } public function students(): BelongsToMany { return $this->belongsToMany(User::class,'class_enrollments','course_class_id','student_id')->withTimestamps(); } public function announcements(): HasMany { return $this->hasMany(Announcement::class); } public function resources(): HasMany { return $this->hasMany(Resource::class); } }

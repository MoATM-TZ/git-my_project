<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class StudentProfile extends Model { protected $fillable=['full_name','registration_number','year_of_study','phone_number','department','program_level','program_name']; public function user(): BelongsTo { return $this->belongsTo(User::class); } }

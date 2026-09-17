<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LecturerProfile extends Model { protected $fillable=['full_name','department','phone_number']; public function user(): BelongsTo { return $this->belongsTo(User::class); } }

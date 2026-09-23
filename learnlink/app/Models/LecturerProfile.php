<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LecturerProfile extends Model { protected $fillable=['full_name','department','phone_number','verified_at','verified_by']; protected function casts(): array { return ['verified_at'=>'datetime']; } public function user(): BelongsTo { return $this->belongsTo(User::class); } public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); } }

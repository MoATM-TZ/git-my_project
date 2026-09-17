<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class AcademicSetting extends Model { protected $fillable=['academic_year','semester','is_current']; protected function casts(): array{return ['is_current'=>'boolean'];} }

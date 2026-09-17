<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** Learning materials this user has uploaded. */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /** Comments posted across the platform. */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** Votes cast on other people's resources (and their own). */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /** Moderation reports filed by this user. */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /** Append-only reputation ledger entries earned by this user. */
    public function reputations(): HasMany
    {
        return $this->hasMany(Reputation::class);
    }
    public function studentProfile(): HasOne { return $this->hasOne(StudentProfile::class); }
    public function lecturerProfile(): HasOne { return $this->hasOne(LecturerProfile::class); }
    public function taughtClasses(): HasMany { return $this->hasMany(CourseClass::class, 'lecturer_id'); }
    public function enrolledClasses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany { return $this->belongsToMany(CourseClass::class, 'class_enrollments', 'student_id', 'course_class_id')->withTimestamps(); }
}

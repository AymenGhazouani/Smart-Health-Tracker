<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class PsyNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'psy_session_id',
        'psychologist_id',
        'content',
        'note_type',
        'is_encrypted',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Validation rules for the model
     */
    public static function validationRules(): array
    {
        return [
            'psy_session_id' => 'required|exists:psy_sessions,id',
            'psychologist_id' => 'required|exists:psychologists,id',
            'content' => 'required|string|max:10000|min:1',
            'note_type' => 'required|in:session_notes,assessment,follow_up,treatment_plan,progress_notes,other',
            'is_encrypted' => 'sometimes|boolean',
        ];
    }

    /**
     * Get the session this note belongs to
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PsySession::class, 'psy_session_id');
    }

    /**
     * Get the psychologist who wrote this note
     */
    public function psychologist(): BelongsTo
    {
        return $this->belongsTo(Psychologist::class);
    }

    /**
     * Scope to filter by note type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('note_type', $type);
    }

    /**
     * Scope to filter by psychologist
     */
    public function scopeByPsychologist($query, $psychologistId)
    {
        return $query->where('psychologist_id', $psychologistId);
    }

    /**
     * Encrypt the content before saving
     */
    public function setContentAttribute($value)
    {
        if ($this->is_encrypted) {
            $this->attributes['content'] = Crypt::encryptString($value);
        } else {
            $this->attributes['content'] = $value;
        }
    }

    /**
     * Decrypt the content when retrieving
     */
    public function getContentAttribute($value)
    {
        if ($this->is_encrypted && !empty($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                // If decryption fails, return the raw value
                return $value;
            }
        }
        
        return $value;
    }

    /**
     * Get decrypted content (alias for getContentAttribute)
     */
    public function getDecryptedContent()
    {
        return $this->content;
    }

    /**
     * Check if the current user can access this note
     */
    public function canBeAccessedBy($userId)
    {
        // Only the psychologist who wrote the note can access it
        return $this->psychologist_id == $userId;
    }

    /**
     * Get note types
     */
    public static function getNoteTypes()
    {
        return [
            'session_notes' => 'Session Notes',
            'assessment' => 'Assessment',
            'follow_up' => 'Follow-up',
            'treatment_plan' => 'Treatment Plan',
            'progress_notes' => 'Progress Notes',
            'other' => 'Other',
        ];
    }
}


<?php

namespace App\Models;

use Database\Factories\CsvUploadFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property int $id
 * @property string $file_name
 * @property string $file_path
 * @property int $uploaded_by
 * @property string $uploaded_at
 * @property array|null $field_mapping
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, CsvField> $csvFields
 * @property-read int|null $csv_fields_count
 * @method static CsvUploadFactory factory($count = null, $state = [])
 * @method static Builder<static>|CsvUpload newModelQuery()
 * @method static Builder<static>|CsvUpload newQuery()
 * @method static Builder<static>|CsvUpload onlyTrashed()
 * @method static Builder<static>|CsvUpload query()
 * @method static Builder<static>|CsvUpload whereCreatedAt($value)
 * @method static Builder<static>|CsvUpload whereDeletedAt($value)
 * @method static Builder<static>|CsvUpload whereFieldMapping($value)
 * @method static Builder<static>|CsvUpload whereFileName($value)
 * @method static Builder<static>|CsvUpload whereFilePath($value)
 * @method static Builder<static>|CsvUpload whereId($value)
 * @method static Builder<static>|CsvUpload whereUpdatedAt($value)
 * @method static Builder<static>|CsvUpload whereUploadedAt($value)
 * @method static Builder<static>|CsvUpload whereUploadedBy($value)
 * @method static Builder<static>|CsvUpload withTrashed()
 * @method static Builder<static>|CsvUpload withoutTrashed()
 * @property-read User $userUploaded
 * @mixin \Eloquent
 */
class CsvUpload extends Model
{
   use SoftDeletes, HasFactory;

    protected $fillable = [
         'file_name',
         'file_path',
         'uploaded_by',
         'field_mapping',
         'uploaded_at',
    ];

    protected $casts = [
        'field_mapping' => 'array',
    ];
	
	public function csvFields(): HasMany
	{
		return $this->hasMany(CsvField::class);
	}
	
	public function userUploaded(): BelongsTo
	{
		return $this->belongsTo(User::class, 'uploaded_by', 'id');
	}
}

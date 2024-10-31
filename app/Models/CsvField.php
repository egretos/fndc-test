<?php

namespace App\Models;

use Database\Factories\CsvFieldFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property int $id
 * @property int $csv_upload_id
 * @property array $field_data
 * @property string $validation_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\CsvUpload $csvUpload
 * @method static CsvFieldFactory factory($count = null, $state = [])
 * @method static Builder<static>|CsvField newModelQuery()
 * @method static Builder<static>|CsvField newQuery()
 * @method static Builder<static>|CsvField query()
 * @method static Builder<static>|CsvField whereCreatedAt($value)
 * @method static Builder<static>|CsvField whereCsvUploadId($value)
 * @method static Builder<static>|CsvField whereFieldData($value)
 * @method static Builder<static>|CsvField whereId($value)
 * @method static Builder<static>|CsvField whereUpdatedAt($value)
 * @method static Builder<static>|CsvField whereValidationStatus($value)
 * @mixin \Eloquent
 */
class CsvField extends Model
{
    use hasFactory;
	
	const STATUS_PENDING = 'pending';
	const STATUS_VALID = 'valid';
	const STATUS_INVALID = 'invalid';
	const STATUS_ERROR = 'error';
	
	const ADDRESS_FIELD_NAME = 'address';
	
    protected $fillable = [
        'csv_upload_id',
        'field_data',
        'validation_status',
    ];

    protected $casts = [
        'field_data' => 'array',
    ];

    public function csvUpload(): BelongsTo
    {
        return $this->belongsTo(CsvUpload::class);
    }
	
	public function isPending(): bool
	{
		return $this->validation_status === self::STATUS_PENDING;
	}
}

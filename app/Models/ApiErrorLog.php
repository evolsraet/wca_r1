<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiErrorLog extends Model
{
    protected $table = 'api_error_logs';

    protected $fillable = [
        'job_name',
        'method',
        'url',
        'payload',
        'response_body',
        'error_message',
        'trace',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
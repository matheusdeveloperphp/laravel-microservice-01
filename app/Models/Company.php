<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'uuid',
        'name',
        'url',
        'phone',
        'whatsapp',
        'email',
        'facebook',
        'instagram',
        'youtube',
        'image',
    ];

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * @param string $filter
     * @return LengthAwarePaginator
     */
    public function getFilterCompanies(string $filter = '')
    {
        $companies = $this->with('category')
            ->where(function ($query) use ($filter) {
                if ($filter != '') {
                    $query->where('name', 'LIKE', "%{$filter}%");
                    $query->orWhere('email', '=', $filter);
                    $query->orWhere('phone', '=', $filter);
                }
            })
            ->paginate();

        return $companies;
    }
}

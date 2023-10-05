<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'code',
        'user_id',
    ];

    public function views()
    {
        return $this->hasMany(LinkView::class);
    }
    public static function getUrl($code)
    {
        $cacheKey = "url:$code";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $url = self::where('code',$code)->first();

        Cache::put($cacheKey, $url);

        return $url;
    }

    public static function incrementViewCount($code)
    {
        $url = self::getUrl($code);
        $url->increment('views_count');

        $cacheKey = "url:$code";
        Cache::put($cacheKey, $url);

        return $url;
    }
}

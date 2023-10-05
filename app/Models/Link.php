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

    public static function incrementViewCount($link = null, $code = null)
    {
        if(!$link)
            $link = self::getUrl($code);

        $link->increment('views_count');

        $cacheKey = "url:{$link->code}";
        Cache::put($cacheKey, $link);

        return $link;
    }
    public static function updateCount($code, $count)
    {

        $cacheKey = "url:{$code}";

        if (Cache::has($cacheKey)) {
            $link = Cache::get($cacheKey);

            $link->views_count = $count;

            Cache::put($cacheKey, $link);
        }
    }
}

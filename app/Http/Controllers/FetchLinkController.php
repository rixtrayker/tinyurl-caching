<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FetchLinkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $code)
    {
        $link = Link::getUrl($code);
        if(!$link)
            return abort(404);

        LinkView::create([
            'link_id' => $link->id,
            'visitor_address' => $request->ip(),
        ]);

        $link = Link::incrementViewCount($link);

        return redirect($link->link);
    }
}

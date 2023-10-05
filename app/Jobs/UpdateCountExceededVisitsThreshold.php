<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\LinkView;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateCountExceededVisitsThreshold implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $links = Link::select('links.id as id, links.code as code, links.views_count as old_view_count')
            ->addSelect(DB::raw('COUNT(link_views.id) as new_view_count'))
            ->addSelect(DB::raw('MAX(link_views.id) as latest_view_id'))
            ->leftJoin('link_views', 'links.id', '=', 'link_views.link_id')
            ->groupBy('links.id')
            ->havingRaw('new_view_count >= ?', [ 100 ])
            ->get();

        //update count threshold is 100

        foreach ($links as $link) {
            $totalCount = $link->old_view_count + $link->new_view_count;

            Link::where('id',$link->id)
                ->update(['views_count' => $totalCount]);

            Link::updateCount($link->id, $totalCount);
            LinkView::where('id','<=',$link->latest_view_id)->delete();
        }
    }
}

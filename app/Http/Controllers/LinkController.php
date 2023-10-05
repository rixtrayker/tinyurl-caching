<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get only mine
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get if its mine or return 403

        $link = Link::find($id);
        if(!$link){
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return LinkResource::make($link);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $link = Link::find($id);

        if(!$link){
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if($link->user_id != auth()->id()){

        }

        $link->update([$request->link]);

        return LinkResource::make($link);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = Link::find($id);

        if(!$link){
            $this->unAuthorized();
        }

        if($link->user_id != auth()->id()){
            return response()->json([
                'status' => false,
                'message' => 'Not authorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $link->delete();

        return LinkResource::make($link);
    }

    private function unAuthorized() {
        return response()->json([
            'status' => false,
            'message' => 'Not authorized'
        ], Response::HTTP_UNAUTHORIZED);
    }
}

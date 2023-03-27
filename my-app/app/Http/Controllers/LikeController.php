<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $cluster_id=$request->id;
        $user = $request->user();
        $user->like($cluster_id);
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cluster;
use App\Models\User;
use App\Models\Like;
class ClusterController extends Controller
{
    public function createCluster(Request $request){
        $user = $request->user();
        $userId = $user["id"];
        print($userId);
        $newCluster=Cluster::create([
            "message"=>$request->message,
            "latitude"=>$request->latitude,
            "longitude"=>$request->longitude,
            "user_id"=>$userId
        ]);
        print($newCluster);


    }

    public function getLikes(Request $request){
        $id=$request->user_id;
        $likes = Like::select("cluster_id")->where("user_id", '=', $id)->get();

        $result=[];
        foreach ($likes as $like){
            $id=$like->cluster_id;

            $cluster=Cluster::select("id","latitude","longitude")
            ->find($id);
            $result[]=$cluster;
        }
        return $result;

    }

    public function getClusters(Request $request){
        $lat=$request->lat;
        $lng=$request->lng;
        $keys=$request->keys;
        $array = explode("/",$keys);




        $result=Cluster::select("id","latitude","longitude")
        ->whereBetween('latitude',[$lat-1,$lat+1])
        ->whereBetween('longitude',[$lng-1,$lng+1])
        ->where('message',"LIKE","%$array[0]%")
        ->when(isset($array[1]), function ($query) use ($array) {
            return $query->where('message',"like","%$array[1]%");
        })
        ->when(isset($array[2]), function ($query)use ($array) {
            return $query->where('message',"like","%$array[2]%");
        })
        ->when(isset($array[3]), function ($query)use ($array) {
            return $query->where('message',"like","%$array[3]%");
        })
        ->when(isset($array[4]), function ($query)use ($array) {
            return $query->where('message',"like","%$array[4]%");
        })
        ->get();


        return json_encode($result);


    }
    public function getACluster(Request $request){
        $user=$request->user();
        $cluster_id=$request->id;
        $result=Cluster::find($cluster_id);
        $likes = $result->likes();
        $is_like=$user->isLike($cluster_id);
        $is_mine=($user->id==$result->id);
        return response()->json([
            "message"=>$result->message,
            "created_at"=>$result->created_at,
            "likes"=>$likes,
            "user_id"=>$result->user_id,
            "user_name"=>$user->name,
            "is_like"=>$is_like,
            "is_mine"=>$is_mine
        ]);



        return json_encode($result);


    }

    public function update_cluster(Request $request){
        $new_message=$request->message;
        $cluster_id=$request->cluster_id;
        $cluster=Cluster::find($cluster_id);
        $cluster->message = $new_message;
        $cluster->save();
    }

    public function delete_cluster(Request $request){

        if($request->user_id==null){
            $result=Cluster::where('id',$request->cluster_id)->delete();
            return $result;
        }else{
            $result=Cluster::where('user_id',$request->user_id)->delete();
        }
    }



}

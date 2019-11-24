<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\News;
use App\Http\Requests\NewsRequest;

class NewsController extends Controller
{
    public function index()
    {
        $news = new News();
        $news->setTable(Auth::User()->prefix.'_news');
        $news = $news->get();

        return response()->json([
            'message' => 'Success',
            'data' => $news
        ], 201); 
        
    }

    public function store(NewsRequest $request)
    {
        $news = new News([
            'author_id' => Auth::User()->id,
            'title' => $request->title,
            'description' => $request->description
        ]);
        $news->setTable(Auth::User()->prefix.'_news');
        $news->save();
        return response()->json([
            'message' => 'Success, data inserted!'
        ], 201);  
        
    }

    public function edit($id)
    {
        
        $news = new News();
        $news->setTable(Auth::User()->prefix.'_news');
        $news = $news->get()->where('id',$id);
        return response()->json([
            'message' => 'Success, found data!',
            'data' => $news
        ], 201);  
    }

    public function update(NewsRequest $request, $id)
    {
        $news = new News();
          
        $news->setTable(Auth::User()->prefix.'_news');
        $news->where('id',$id)
                ->update([
                'title' => $request->title,
                'description' => $request->description
                ]);

        return response()->json([
            'message' => 'Success, data updated!'], 201);
        
    }

    public function delete($id)
    {
        $news = new News();
        $news->setTable(Auth::User()->prefix.'_news');
        $news->delete();
        return response()->json([
            'message' => 'Success, data deleted'], 201);
        
    }
}

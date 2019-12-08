<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\News;
use App\Http\Requests\StoreNews;
use App\Http\Requests\UpdateNews;
use App\Services\PivotService;

class NewsController extends Controller
{

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function index(PivotService $pivot)
    {
        //Auth::User()->prefix = $request->prefix;
        $this->news->setTable(Auth::User()->prefix.'_news');
        $news = $this->news->get();
        $pivot->delete();
        
        return response()->json([
            'message' => 'Success',
            'data' => $news
        ], 201); 
    }

    public function store(StoreNews $request, PivotService $pivot)
    {
        $this->news->setTable(Auth::User()->prefix.'_news');
        $this->news->create(['author_id' => Auth::User()->id] + $request->validated());
        
        $pivot->store();

        return response()->json([
            'message' => 'Success, data inserted!'
        ], 201);  
    }

    public function edit(Request $request)
    {
        $this->news->setTable(Auth::User()->prefix.'_news');
        $news = $this->news->get()->where('id',$request->id);
        return response()->json([
            'message' => 'Success, found data!',
            'data' => $news
        ], 201);  
    }

    public function update(UpdateNews $request)
    {   

        $this->news->setTable(Auth::User()->prefix.'_news');
        $this->news->where('id',$request->id)->update($request->validated());

        return response()->json([
            'message' => 'Success, data updated!'], 201);
    }

    public function delete(Request $request)
    {
        $this->news->setTable(Auth::User()->prefix.'_news');
        $this->news->where('id',$request->id)->delete();
        return response()->json([
            'message' => 'Success, data deleted'], 201);
    }
}

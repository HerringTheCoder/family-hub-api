<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Gallery;
use Illuminate\Support\Facades\Gate;
use DB;
use App\Http\Requests\StoreGallery;
use App\Http\Requests\UpdateGallery;

class GalleryController extends Controller
{
    public function __construct(Gallery $gallery)
    {
        $this->gallery = $gallery;
    }

    public function index()
    {
        $this->gallery->setTable(Auth::User()->prefix.'_gallery');
        $gallery = $this->gallery->get();

        return response()->json([
            'message' => 'Success',
            'data' => $gallery
        ], 201); 
        
    }

    public function store(Request $request)
    {
        $photo = $request->file('photo_input');
        $extension = $photo->getClientOriginalExtension();
        Storage::disk('public')->put($photo->getFilename().'.'.$extension,  File::get($photo));
        $filename = $photo->getFilename().'.'.$extension;

        $gallery = new Gallery([
            'author_id' => Auth::User()->id,
            'mime' => $photo->getClientMimeType(),
            'original_filename' => $photo->getClientOriginalName(),
            'filename' => $filename,
            'description' => $request->description
        ]);
        $gallery->setTable(Auth::User()->prefix.'_gallery');
        $gallery->save();
        return response()->json([
            'message' => 'Success, data inserted!'
        ], 201);  
        
    }

    // public function edit($id)
    // {
        
    //     $gallery = new Gallery();
    //     $gallery->setTable(Auth::User()->prefix.'_gallery');
    //     $gallery = $gallery->get()->where('id',$id);
    //     return response()->json([
    //         'message' => 'Success, found data!',
    //         'data' => $gallery
    //     ], 201);  
    // }

    // public function update(Request $request, $id)
    // {
    //     $photo = $request->file('photo_input');
        
    //     if($photo){
    //         $extension = $photo->getClientOriginalExtension();
    //         Storage::disk('public')->put($photo->getFilename().'.'.$extension,  File::get($photo));

    //         $filename = $photo->getFilename().'.'.$extension;
    //         $mime = $photo->getClientMimeType();
    //         $original_filename = $photo->getClientOriginalName();
    //     }else{
    //         $filename = $request->photo;
    //         $mime = $request->mime;
    //         $original_filename = $request->original_filename;
    //     }

    //     $gallery = new Gallery();
          
    //     $gallery->setTable(Auth::User()->prefix.'_gallery');
    //     $gallery->where('id',$id)
    //             ->update([
    //             'mime' => $mime,
    //             'original_filename' => $original_filename,
    //             'filename' => $filename,
    //             'description' => $request->description
    //             ]);

    //     return response()->json([
    //         'message' => 'Success, data updated!'], 201);
        
    // }

    public function delete(Request $request)
    {

        $photo = DB::table(Auth::User()->prefix.'_gallery')->where('id', $request->id)->first();
        //$this->authorize('update',Auth::user(), $request);
        if (Gate::allows('delete-photo', $photo)) {

            $this->gallery->setTable(Auth::User()->prefix.'_gallery');
            $this->gallery->where('id',$request->id)->delete();
            return response()->json([
                'message' => 'Success, data deleted'], 201);
            }else{
                return response()->json([
                    'message' => 'You are not authorized to delete this photo!'], 403);
            }
       
        
    }
}

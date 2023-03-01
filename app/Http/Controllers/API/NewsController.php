<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::query()->where('id_user', Auth::user()->id)->latest()->get();

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $news
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required|max:50',
    //         'banner' => 'required|image|mimes:jpeg,png,jpg',
    //         'content' => 'required'
    //     ], $messages = [
    //         'image' => 'Wajib Gambar',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             "status" => false,
    //             "message" => $validator->errors(),
    //             "data" => ""
    //         ]);
    //     }

    //     $news = new News();

    //     $news->fill($request->all());
    //     $banner = $request->file('banner');
    //     $filename = $banner->hashName();
    //     $banner->move("banner", $filename);
    //     $path = $request->getSchemeAndHttpHost() . "/banner/" . $filename;
    //     $news->banner = $path;
    //     $news->id_user = Auth::user()->id;

    //     $news->save();

    //     return response()->json([
    //         "status" => true,
    //         "message" => "Berhasil Menambahkan berita",
    //         "data" => $news
    //     ]);
    // }
    
//     testing
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'banner' => 'required|image|mimes:jpeg,png,jpg',
            'content' => 'required'
        ], $messages = [
                    'image' => 'Wajib Gambar',
                ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors(),
                "data" => ""
            ]);
        }

        $news = new News();

        $news->fill($request->all());
        // $news['banner'] = request()->file('banner')->store('banners');
        $news['banner'] = request()->file('banner')->store('banners', 'public');
        $news->id_user = Auth::user()->id;

        $news->save();

        return response()->json([
            "status" => true,
            "message" => "Berhasil Menambahkan berita",
            "data" => $news
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::query()->where('id', $id)->where('id_user', Auth::user()->id)->first();

        if ($news == null) {
            return response()->json([
                "status" => false,
                "message" => "Data kosong",
                "data" => ""
            ]);
        }
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $news
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Mendapatkan data news
        $news = News::query()->where('id', $id)->where('id_user', Auth::user()->id)->get()->first();

        $validator = Validator::make($request->all(), [
            'banner' => 'image|mimes:jpeg,png,jpg',
            'title' => 'required',
            'content' => 'required'
        ], $messages = [
            'image' => 'Wajib Gambar',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors(),
                "data" => ""
            ]);
        }
        $banner = $request->file('banner');
        if ($banner != null) {

            Storage::disk('public')->delete($news->banner);

            $news->fill($request->all());
            $news['banner'] = request()->file('banner')->store('banners', 'public');

            $news->save();

            return response()->json([
                "status" => true,
                "message" => "Berhasil mengupdate news",
                "data" => $news
            ]);
        } else {
            $news->fill($request->all());
            $news->save();

            return response()->json([
                "status" => true,
                "message" => "Berhasil mengupdate news",
                "data" => $news
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::query()->where("id", $id)->where("id_user", Auth::user()->id)->first();

        Storage::disk('public')->delete($news->banner);
        $news->delete();

        return response()->json([
            "status" => true,
            "message" => "Berhasil menghapus news",
            "data" => $news
        ]);
    }
}

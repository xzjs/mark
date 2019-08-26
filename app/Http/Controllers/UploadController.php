<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path = $request->file('img')->store('public');
        $type = $request->input('type');
        switch ($type) {
            case 'ocr':
                $output['ocr'] = $this->ocr($request->file('img'));
                break;
            default:
                break;
        }
        $path = Storage::url($path);
        $output['path'] = $path;
        return response()->json($output);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function ocr(\Illuminate\Http\UploadedFile $file)
    {
        $accessToken = $this->getAccessToken();
        $image = base64_encode($file->get());
        $params = ['access_token' => $accessToken];
        $url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?' . http_build_query($params);
        $client = new Client();
        $response = $client->post($url, ['form_params' => ['image' => $image]]);
        $data = json_decode($response->getBody(), true);
        $ocrString = '';
        foreach ($data['words_result'] as $word) {
            $ocrString .= $word['words'];
        }
        return $ocrString;
    }

    protected function getAccessToken()
    {
        $accessToken = Redis::get('accessToken');
        if (is_null($accessToken)) {
            $client = new Client();
            $params = [
                'grant_type' => 'client_credentials',
                'client_id' => env('API_KEY'),
                'client_secret' => env('CLIENT_SECRET'),
            ];
            $url = 'https://aip.baidubce.com/oauth/2.0/token?' . http_build_query($params);
            $res = $client->post($url, []);
            $data = json_decode($res->getBody(), true);
            if (isset($data['error'])) {
                return response($data['error_description'], 500);
            } else {
                $accessToken = $data['access_token'];
                $expires = $data['expires_in'];
                Redis::setex('accessToken', $expires, $accessToken);
            }
        }
        return $accessToken;
    }
}

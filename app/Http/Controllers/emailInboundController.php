<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

use App\User;
use App\email;

use Illuminate\Support\Facades\DB;
use App\SupportTicket;

/*require 'vendor/autoload.php';*/
use Mailgun\Mailgun;

use GuzzleHttp\Client;

class emailInboundController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = collect(json_decode($request->attachments, true));
        
        if ($files->count() === 0) {
            $nTicket = new email();
            $nTicket->userId      = 1;                
            $nTicket->message      = "Sin adjuntos";
        }else{

            foreach ($files as $file){
                $fileName = $file['name'];
                $url = $file['url'];

                $msg = $fileName ."::". $url;

                $nTicket = new email();
                $nTicket->userId      = 1;                
                $nTicket->message      = $msg;
                $nTicket->save();
                /*$content = $mg->getAttachment($file['url'])->http_response_body;*/
            }


            /*$httpClient = new Client();
            $response = $httpClient->get($attachment['url'], [
                'auth' => ['api', env("MAILGUN_SECRET")], 
            ]);
            $imageData = (string)$response->getBody();
            $base64 = base64_encode($imageData);
            return $base64;*/
        }

        /*if ( $nTicket->save() ){
            return response()->json(['status' => 'ok', 'message' => $request]);
        }else {
            return response()->json(['status' => 'error', 'message' => $request]);
        }*/

        return response()->json(['status' => 'ok', 'message' => $request]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

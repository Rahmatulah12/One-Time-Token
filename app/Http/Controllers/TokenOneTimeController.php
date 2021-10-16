<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TokenOneTimeService;

class TokenOneTimeController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new TokenOneTimeService();
    }

    public function index()
    {   
        // generateToken($wf_entity, $expired => day, $role)
        $token = $this->service->generateToken("request", 1, "Principal-Pusat");
        
        // setelah generate token, simpan token untuk merevoked nya setelah dipakai
        // token dan expired
        $save = $this->service->save($token, 1);

        $link = url("/test?token=$token");

        return $link;
    }

    public function test(Request $request)
    {
        $validate = $this->service->validationToken($request->token);
        
        // jika tidak valid return 401
        if(!$validate)
        {  
            abort(401);
        }

        // decode token bisa dipake untuk data approval
        $decodeToken = $this->service->decodeToken($request->token);

        $revokedToken = $this->service->revokedToken($request->token);
        dd($revokedToken);
        return response()->json($decodeToken, 200);
    }
}

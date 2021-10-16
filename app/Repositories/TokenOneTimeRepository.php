<?php
namespace App\Repositories;

use App\Models\TokenOneTime;
use Illuminate\Support\Str;

class TokenOneTimeRepository 
{
    private $modelToken;
    public function __construct()
    {
        $this->modelToken = new TokenOneTime();
    }

    /**
     * @param string $token
     * @return object | null
     */
    public function findOneByToken(?string $token)
    {
        return $this->modelToken->where('token', $token)->first();
    }

    /**
     * @param array $data
     */
    public function save(?array $data) 
    {
        if(is_null($data)) return false;

        return $this->modelToken->insert([
            "id" => Str::uuid(),
            "token" => $data['token'],
            "revoked" => false,
            "expired_at" => $data['expired_at'],
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * @param string $token
     */
    public function revokedToken(?string $token) 
    {
        if(is_null($token)) return false;

        return $this->modelToken->where('token', $token)->update([
            "revoked" => true,
            "updated_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
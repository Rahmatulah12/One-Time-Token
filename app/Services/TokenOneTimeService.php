<?php
namespace App\Services;

use Firebase\JWT\JWT;
use App\Repositories\TokenOneTimeRepository;
use RuntimeException;

class TokenOneTimeService
{

    private $repo;

    public function __construct()
    {
        $this->repo = new TokenOneTimeRepository();
    }

    /**
     * @param string $wf_entity => required,
     * @param int $expired => required, 
     * @param string role => optional, if null default value simfoni
     * @return string token
     */
    public function generateToken(string $wf_entity, int $expired, 
        ?string $role = "simfoni") :string
    {
        $expired_at = time() + ( (60 * 60 * 24) * $expired );

        // setting payload
        $payload = [
            "wf_entity" => $wf_entity,
            "role" => $role,
            "exp" => $expired_at,
        ];

        JWT::$leeway = 60;
        $token = JWT::encode($payload, env("JWT_SECRET_KEY"));
        
        return $token;
    }

    /**
     * @param string $token => required
     * @param int expired => required
     */
    public function save(string $token, int $expired)
    {
        $expired_at = time() + ( (60 * 60 * 24) * $expired );

        $data = [
            "token" => $token,
            "expired_at" => date('Y-m-d H:i:s', $expired_at),
        ];

        return $this->repo->save($data);
    }

    public function revokedToken(?string $token)
    {
        if(is_null($token)) return false;

        $dataToken = $this->repo->findOneByToken($token);

        if(!$dataToken)
        {
            return false;
        }

        return $this->repo->revokedToken($token);
    }

    /**
     * @param string token
     * @return boolean 
     * if valid is true
     * not valid is false
     */
    public function validationToken(?string $token)
    {
        if(is_null($token)) return false;

        try{
            $decode = JWT::decode($token, env("JWT_SECRET_KEY"), array('HS256'));
            
            // check expired
            if(!$this->checkExpiredToken((int) $decode->exp))
            {
                return false;
            }

            $payload = $this->checkRevokedToken($token);

            if(is_null($payload)) {
                return false;
            }
            
            if($payload->revoked)
            {
                return false;
            }

            return true;

        } catch(RuntimeException $err) {
            throw $err;
        }
    }

    public function checkExpiredToken(int $expired)
    {
        if(time() > $expired)
        {
            return false;
        }

        return true;
    }

    public function decodeToken(?string $token)
    {
        $decode = JWT::decode($token, env("JWT_SECRET_KEY"), array('HS256'));
        return $decode;
    }

    public function checkRevokedToken(?string $token)
    {
        if(is_null($token)) return false;

        $find = $this->repo->findOneByToken($token);

        return $find;
    }
}
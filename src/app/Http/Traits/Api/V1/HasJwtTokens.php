<?php

namespace App\Http\Traits\Api\V1;

use DateTimeImmutable;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use App\Models\User;
use App\Models\JwtToken;
trait HasJwtTokens
{
    public function configuration()
    {
        $private_key = config('jwt.private_key');
        $public_key = config('jwt.public_key');

        $config = Configuration::forAsymmetricSigner(
            new Signer\Rsa\Sha256(),
            InMemory::plainText($private_key),
            InMemory::plainText($public_key),
        );

        return $config;
    }
    protected function createToken($uuid)
    {
        $dateNow   = new DateTimeImmutable();

        $tokenBuilder = $this->configuration()->builder();

        $newtoken = $tokenBuilder
            ->issuedBy(env('APP_URL'))
            ->issuedAt($dateNow)
            ->expiresAt($dateNow->modify('+10 minute'))
            ->withClaim('uuid', $uuid)
            ->withHeader('pet', 'shop')
            ->getToken($this->configuration()->signer(),$this->configuration()->signingKey());

        $user = User::where('uuid',$uuid)->first();

        JwtToken::create([
            'user_id' => $user->id,
            'unique_id' => $user->uuid,
            'token_title' => $newtoken->toString()
        ]);

        return $newtoken->toString();

    }

    protected function jwtAuthenticatedUser($jwt)
    {
        $parser = $this->configuration()->parser();

        $token = $parser->parse($jwt);

        $validator = $this->configuration()->validator();

        if ($validator->validate($token, new SignedWith($this->configuration()->signer(),$this->configuration()->verificationKey()))) {

            return User::where('uuid',$token->claims()->get('uuid'))->first();

        }

        return null;
    }



}
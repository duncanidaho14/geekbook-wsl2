<?php 

namespace App\Mercure;

use Symfony\Component\Mercure\Jwt\TokenProviderInterface;

final class JwtProvider implements TokenProviderInterface
{
    public function getJwt(): string
    {
        return '!ChangeThisMercureHubJWTSecretKey!';
    }
}
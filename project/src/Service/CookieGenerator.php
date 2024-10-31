<?php

namespace App\Service;

use App\Entity\Book;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use RuntimeException;
use Symfony\Component\HttpFoundation\Cookie;

class CookieGenerator
{
    public function __construct(private readonly string $secret){}

    public function generate(Book $book): Cookie {
        if (empty($this->secret)) {
            throw new RuntimeException('The secret cannot be empty');
        }

        $topic = "/livre/{$book->getSlug()}";
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($this->secret)
        );

        $token = $config->builder()
                        ->withClaim('mercure', ['subscribe' => [$topic]])
                        ->getToken($config->signer(), $config->signingKey())
                        ->toString();
        return Cookie::create('mercureAuthorization', $token, 0, '/.well-known/mercure');
    }
}
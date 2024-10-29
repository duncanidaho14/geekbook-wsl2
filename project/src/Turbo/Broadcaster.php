<?php
// src/Turbo/Broadcaster.php
namespace App\Turbo;

use ReflectionClass;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\UX\Turbo\Broadcaster\BroadcasterInterface;

class Broadcaster implements BroadcasterInterface
{
    public function broadcast(object $entity, string $action, array $options): void
    {
        // This method will be called every time an object marked with the #[Broadcast] attribute is changed
        $attribute = (new ReflectionClass($entity))->getAttributes(Broadcast::class)[0] ?? null;
        // ...
    }
}
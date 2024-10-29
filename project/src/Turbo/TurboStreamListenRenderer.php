<?php

namespace App\Turbo;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Symfony\UX\Turbo\Twig\TurboStreamListenRendererInterface;
use Twig\Environment;

#[AsTaggedItem(index: 'my-transport')]
class TurboStreamListenRenderer implements TurboStreamListenRendererInterface
{
    public function __construct(
        private StimulusHelper $stimulusHelper,
    ) {}

    public function renderTurboStreamListen(Environment $env, $topic): string
    {
        $stimulusAttributes = $this->stimulusHelper->createStimulusAttributes();
        $stimulusAttributes->addController('controllers/', [
            /* controller values such as topic */
            
        ]);

        return (string) $stimulusAttributes;
    }
}
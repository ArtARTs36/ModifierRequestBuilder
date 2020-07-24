<?php

namespace ArtARTs36\ModifierRequestBuilder\Tests\Traits;

use ArtARTs36\ModifierRequestBuilder\Contracts\ExternalConfig;

trait MakeExternalConfig
{
    protected function makeExternalConfig(): ExternalConfig
    {
        return new class implements ExternalConfig {
            /**
             * @inheritDoc
             */
            public function delimiterFields(): string
            {
                return ',';
            }

            /**
             * @inheritDoc
             */
            public function delimiterAssociations(): string
            {
                return ':';
            }
        };
    }
}

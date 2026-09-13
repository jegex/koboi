<?php

namespace Jegex\Koboi\Testing\Browser\Concerns;

use Facebook\WebDriver\Exception\TimeOutException;
use Jegex\Koboi\Testing\Browser\Components\Modals\CreateRelationModalComponent;
use Laravel\Dusk\Browser;

trait InteractsWithInlineCreateRelation
{
    /**
     * Run the inline relation.
     *
     * @throws TimeOutException
     */
    public function showInlineCreate(Browser $browser, string $uriKey, callable $fieldCallback): void
    {
        $browser->whenAvailable("@{$uriKey}-inline-create", static function (Browser $browser) use ($fieldCallback) {
            $browser->click('')
                ->elsewhereWhenAvailable(new CreateRelationModalComponent, static function (Browser $browser) use ($fieldCallback) {
                    $fieldCallback($browser);
                });
        });
    }

    /**
     * Run the inline create relation.
     *
     * @throws TimeOutException
     */
    public function runInlineCreate(Browser $browser, string $uriKey, callable $fieldCallback): void
    {
        $this->showInlineCreate($browser, $uriKey, static function (Browser $browser) use ($fieldCallback) {
            $fieldCallback($browser);

            $browser->click('@create-button')->pause(250);
        });
    }
}

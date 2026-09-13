<?php

namespace Jegex\Koboi\Http\Requests;

class UpdateResourceRequest extends NovaRequest
{
    /** {@inheritDoc} */
    #[\Override]
    public function isUpdateOrUpdateAttachedRequest(): bool
    {
        return true;
    }
}

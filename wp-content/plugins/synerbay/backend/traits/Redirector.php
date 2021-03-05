<?php

namespace SynerBay\Traits;

trait Redirector
{
    protected function redirectToUpdateOffer(int $offerID)
    {
        $postFix = 'dashboard/edit-offer/' . $offerID;
        $this->redirectTo($postFix);
    }

    private function redirectTo(string $postFix)
    {
        wp_redirect(site_url()."/".$postFix);
    }
}

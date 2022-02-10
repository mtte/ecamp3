<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[ApiResource]
class PbsmidataController extends AbstractController {
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/auth/pbsmidata", name="connect_pbsmidata_start")
     */
    public function connectAction(Request $request, ClientRegistry $clientRegistry) {
        $request->getSession()->set('redirect_uri', $request->get('callback'));

        return $clientRegistry
            ->getClient('pbsmidata') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect()
        ;
    }

    /**
     * After going to Hitobito, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/auth/pbsmidata/callback", name="connect_pbsmidata_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry) {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a custom authenticator
    }
}

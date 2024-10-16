<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JemkdbController extends AbstractController {
    /**
     * Link to this controller to start the "connect" process.
     */
    #[Route('/auth/jemkdb', name: 'connect_jemkdb_start')]
    public function connectAction(Request $request, ClientRegistry $clientRegistry) {
        return $clientRegistry
            ->getClient('jemkdb') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([], ['additionalData' => ['callback' => $request->get('callback')]])
        ;
    }

    /**
     * After going to Hitobito, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     */
    #[Route('/auth/jemkdb/callback', name: 'connect_jemkdb_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry) {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a custom authenticator
    }
}

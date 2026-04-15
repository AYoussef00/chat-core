<?php

namespace App\Http\Controllers\Channels;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MessengerConnectPageController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('ConnectMessenger', [
            'facebookAccount' => $request->session()->get('messenger.facebook_account'),
            'facebookPages' => $request->session()->get('messenger.facebook_pages', []),
            'selectedFacebookPageId' => $request->session()->get('messenger.selected_facebook_page_id'),
        ]);
    }
}

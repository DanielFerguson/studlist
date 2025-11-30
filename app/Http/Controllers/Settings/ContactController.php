<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ContactUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Show the user's contact settings page.
     */
    public function edit(Request $request): View
    {
        return view('settings.contact', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's contact settings.
     */
    public function update(ContactUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return to_route('contact.edit')->with('success', 'Contact information updated successfully.');
    }
}


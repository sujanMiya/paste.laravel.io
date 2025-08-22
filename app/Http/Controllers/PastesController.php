<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasteRequest;
use App\Models\Paste;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PastesController extends Controller
{
    public function post(PasteRequest $request): RedirectResponse
    {
        $paste = Paste::fromRequest($request);

        return redirect()->route('show', $paste->hash);
    }

    public function show(Paste $paste): View
    {
        if ($paste->isProtected()) {
            $sessionKey = "paste_unlocked_{$paste->hash}";
            if (!session()->has($sessionKey)) {
                return view('password-prompt', compact('paste'));
            }
        }
        return view('show', compact('paste'));
    }

    public function unlock(Request $request, Paste $paste): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if ($paste->checkPassword($request->input('password'))) {
            $sessionKey = "paste_unlocked_{$paste->hash}";
            session()->put($sessionKey, true);

            return redirect()->route('show', $paste->hash);
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    public function raw(Paste $paste): View
    {
        return view('raw', compact('paste'));
    }

    public function edit(Paste $paste): View
    {
        return view('edit', compact('paste'));
    }

    public function fork(PasteRequest $request, Paste $paste): RedirectResponse
    {
        $paste = Paste::fromFork($paste, $request);

        return redirect()->route('show', $paste->hash);
    }
}

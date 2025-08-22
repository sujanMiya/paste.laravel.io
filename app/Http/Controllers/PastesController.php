<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasteProtectionRequest;
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
    public function sessionKeyCreate($paste): string
    {
        return "paste_unlocked_{$paste->hash}";
    }

    public function show(Paste $paste): View
    {
        if ($paste->isProtected()) {
            if (!session()->has($this->sessionKeyCreate($paste))) {
                return view('password-prompt', compact('paste'));
            }
        }
        return view('show', compact('paste'));
    }

    public function unlock(PasteProtectionRequest $request, Paste $paste): RedirectResponse
    {

        if ($paste->checkPassword($request->validated())) {
            session()->put($this->sessionKeyCreate($paste), true);

            return redirect()->route('show', $paste->hash);
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    public function raw(Paste $paste): View
    {
        if ($paste->isProtected()) {

            if (!session()->has($this->sessionKeyCreate($paste))) {
                abort(403, 'This paste is password protected.');
            }
        }
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

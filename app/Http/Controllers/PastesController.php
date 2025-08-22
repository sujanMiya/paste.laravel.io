<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PasteRequest;
use App\Models\Paste;
use App\Services\PastesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PastesController extends Controller
{
    protected PastesService $pastesService;

    public function __construct(PastesService $pastesService)
    {
        $this->pastesService = $pastesService;
    }
    public function home(): View
    {
        try {
            return view("create");
        } catch (\Exception $e) {
            return view("error", ["message" => $e->getMessage()]);
        }
    }
    public function store(PasteRequest $request): RedirectResponse
    {
        try {
            $paste = $this->pastesService->createPaste($request->validated());
            return redirect()->route('show', $paste->hash);
        } catch (\Exception $e) {
            throw new \Exception('Something went wrong!');
        }
    }

    public function show(Paste $paste): View
    {
        try {
            return view('show', compact('paste'));
        } catch (\Exception $e) {
            throw new \Exception('Something went wrong!');
        }
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

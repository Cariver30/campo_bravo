<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExtraController extends Controller
{
    public function index()
    {
        $extras = Extra::orderBy('name')->get();

        return view('extras.index', [
            'extras' => $extras,
            'viewScopes' => Extra::VIEW_SCOPES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        Extra::create($data);

        return redirect($this->redirectTo($request))
            ->with('success', 'Extra creado correctamente.');
    }

    public function edit(Extra $extra)
    {
        return view('extras.edit', [
            'extra' => $extra,
            'viewScopes' => Extra::VIEW_SCOPES,
        ]);
    }

    public function update(Request $request, Extra $extra)
    {
        $data = $this->validatedData($request);

        $extra->update($data);

        return redirect($this->redirectTo($request))
            ->with('success', 'Extra actualizado correctamente.');
    }

    public function destroy(Request $request, Extra $extra)
    {
        $extra->delete();

        return redirect($this->redirectTo($request))
            ->with('success', 'Extra eliminado correctamente.');
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
            'view_scope' => ['required', Rule::in(Extra::VIEW_SCOPES)],
            'active' => ['boolean'],
        ]);
    }

    protected function redirectTo(Request $request): string
    {
        return $request->input('redirect_to', route('extras.index'));
    }
}

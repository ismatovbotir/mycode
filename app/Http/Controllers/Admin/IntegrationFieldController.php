<?php
// app/Http/Controllers/Admin/IntegrationFieldController.php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\IntegrationField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationFieldController
{
    public function index(): View
    {
        $fields = IntegrationField::orderBy('integration_type')->orderBy('sort_order')->paginate(20);

        return view('admin.integration-fields.index', compact('fields'));
    }

    public function create(): View
    {
        $types = ['moysklad' => 'МойСклад', 'bitrix' => 'Bitrix24', '1c' => '1C'];
        $fieldTypes = ['text' => 'Text', 'password' => 'Password', 'url' => 'URL', 'email' => 'Email', 'number' => 'Number', 'select' => 'Select'];

        return view('admin.integration-fields.create', compact('types', 'fieldTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'integration_type' => ['required', 'string', 'in:moysklad,bitrix,1c'],
            'field_key' => ['required', 'string', 'regex:/^[a-z_]+$/', 'max:100'],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:text,password,url,email,number,select'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string', 'max:1000'],
            'is_required' => ['boolean'],
            'sort_order' => ['integer', 'min:0', 'max:999'],
            'is_active' => ['boolean'],
        ]);

        IntegrationField::create([
            'integration_type' => $validated['integration_type'],
            'field_key' => $validated['field_key'],
            'label' => $validated['label'],
            'type' => $validated['type'],
            'placeholder' => $validated['placeholder'],
            'help_text' => $validated['help_text'],
            'is_required' => $validated['is_required'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.integration-fields.index')->with('success', 'Integration field created successfully!');
    }

    public function edit(IntegrationField $integrationField): View
    {
        $types = ['moysklad' => 'МойСклад', 'bitrix' => 'Bitrix24', '1c' => '1C'];
        $fieldTypes = ['text' => 'Text', 'password' => 'Password', 'url' => 'URL', 'email' => 'Email', 'number' => 'Number', 'select' => 'Select'];

        return view('admin.integration-fields.edit', compact('integrationField', 'types', 'fieldTypes'));
    }

    public function update(Request $request, IntegrationField $integrationField): RedirectResponse
    {
        $validated = $request->validate([
            'integration_type' => ['required', 'string', 'in:moysklad,bitrix,1c'],
            'field_key' => ['required', 'string', 'regex:/^[a-z_]+$/', 'max:100'],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:text,password,url,email,number,select'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string', 'max:1000'],
            'is_required' => ['boolean'],
            'sort_order' => ['integer', 'min:0', 'max:999'],
            'is_active' => ['boolean'],
        ]);

        $integrationField->update([
            'integration_type' => $validated['integration_type'],
            'field_key' => $validated['field_key'],
            'label' => $validated['label'],
            'type' => $validated['type'],
            'placeholder' => $validated['placeholder'],
            'help_text' => $validated['help_text'],
            'is_required' => $validated['is_required'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.integration-fields.index')->with('success', 'Integration field updated successfully!');
    }

    public function destroy(IntegrationField $integrationField): RedirectResponse
    {
        $integrationField->delete();

        return redirect()->route('admin.integration-fields.index')->with('success', 'Integration field deleted successfully!');
    }
}

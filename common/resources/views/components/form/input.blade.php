@props([
    'name' => '',
    'label' => null,
    'title' => null,
    'value' => '',
    'type' => 'text',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'placeholder' => '',
    'hint' => null,
    'error' => null,
    'id' => null,
    'class' => '',
])

@php
    $label = $label ?? $title;
    $id = $id ?? $name;
    $hasError = $error || (isset($errors) && $errors->has($name));
    $errorMessage = $error ?? (isset($errors) ? ($errors->first($name) ?? null) : null);
@endphp

<div class="minimal-form-group">
    @if($label)
        <label for="{{ $id }}" class="minimal-label">
            {{ $label }}
            @if($required)
                <span class="required-star">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id }}"
        value="{{ is_array(old($name, $value)) ? json_encode(old($name, $value)) : old($name, $value) }}"
        class="minimal-input {{ $class }} {{ $hasError ? 'is-invalid' : '' }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([]) }}
    >
    
    @if($hint)
        <span class="form-hint">{{ $hint }}</span>
    @endif
    
    @if($hasError && $errorMessage)
        <div class="form-error">
            <i class="bi bi-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif
</div>

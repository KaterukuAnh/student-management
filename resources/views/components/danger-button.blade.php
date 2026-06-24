<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn']) }} style="background:#b3492f;color:#fff">
    {{ $slot }}
</button>

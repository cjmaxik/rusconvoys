<div class="switch" @if(isset($tooltip)) data-toggle="tooltip" title="{{ $tooltip }}" @endif>
    <label>
        {{ $left_label or '' }}
        <input type="checkbox" name="options[{{ $option }}]"
               id="options.{{ $option }}" {{ old('options.' . $option) ?? $options[$option] }}>
        <span class="lever"></span>
        @if (!isset($left_label))
            <br class="hidden-sm-down">
        @endif
        {{ $label }}
    </label>
</div>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'checked' => false, 'on' => 'Active', 'off' => 'Inactive']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'label', 'checked' => false, 'on' => 'Active', 'off' => 'Inactive']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="field">
    <label><?php echo e($label); ?></label>
    <input type="hidden" name="<?php echo e($name); ?>" value="0">
    <label class="switch" data-on="<?php echo e($on); ?>" data-off="<?php echo e($off); ?>">
        <input type="checkbox" name="<?php echo e($name); ?>" value="1" <?php if(old($name, $checked)): echo 'checked'; endif; ?>><span></span><em style="font-style:normal"><?php echo e($on); ?></em>
    </label>
</div>
<?php /**PATH C:\wamp64\www\sanjay-sales\resources\views/components/switch.blade.php ENDPATH**/ ?>
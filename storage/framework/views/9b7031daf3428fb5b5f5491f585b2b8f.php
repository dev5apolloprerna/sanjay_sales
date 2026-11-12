<?php $__env->startSection('title', $item->exists ? 'Edit Video' : 'Add Video'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal6d73850992cd60c91283300eff9d9378 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6d73850992cd60c91283300eff9d9378 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-page','data' => ['title' => $item->exists ? 'Edit Video' : 'Add Video','editing' => $item->exists,'back' => route('admin.videos.index'),'action' => $item->exists ? route('admin.videos.update', $item) : route('admin.videos.store')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->exists ? 'Edit Video' : 'Add Video'),'editing' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->exists),'back' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.videos.index')),'action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->exists ? route('admin.videos.update', $item) : route('admin.videos.store'))]); ?>
    <?php if (isset($component)) { $__componentOriginalae4c123bc9806121d87d234de2f27a3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae4c123bc9806121d87d234de2f27a3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['name' => 'title','label' => 'Video Title','value' => $item->title,'required' => true,'full' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => 'Video Title','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->title),'required' => true,'full' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $attributes = $__attributesOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $component = $__componentOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__componentOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalae4c123bc9806121d87d234de2f27a3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae4c123bc9806121d87d234de2f27a3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['name' => 'youtube_url','type' => 'url','label' => 'YouTube URL','value' => $item->youtube_url,'required' => true,'full' => true,'placeholder' => 'https://www.youtube.com/watch?v=…','hint' => 'Paste the YouTube video link — the thumbnail is taken from YouTube automatically']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'youtube_url','type' => 'url','label' => 'YouTube URL','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->youtube_url),'required' => true,'full' => true,'placeholder' => 'https://www.youtube.com/watch?v=…','hint' => 'Paste the YouTube video link — the thumbnail is taken from YouTube automatically']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $attributes = $__attributesOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $component = $__componentOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__componentOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
    <?php if($item->embed_url): ?>
        <div class="field full"><label>Preview</label>
            <iframe src="<?php echo e($item->embed_url); ?>" style="width:100%;max-width:480px;aspect-ratio:16/9;border:0;border-radius:8px" allowfullscreen loading="lazy"></iframe></div>
    <?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalae4c123bc9806121d87d234de2f27a3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae4c123bc9806121d87d234de2f27a3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['name' => 'sort_order','type' => 'number','label' => 'Display Order','value' => $item->sort_order]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'sort_order','type' => 'number','label' => 'Display Order','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->sort_order)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $attributes = $__attributesOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $component = $__componentOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__componentOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal026c544cfb81717be117829402d47b5a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal026c544cfb81717be117829402d47b5a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.switch','data' => ['name' => 'status','label' => 'Status','checked' => $item->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','label' => 'Status','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal026c544cfb81717be117829402d47b5a)): ?>
<?php $attributes = $__attributesOriginal026c544cfb81717be117829402d47b5a; ?>
<?php unset($__attributesOriginal026c544cfb81717be117829402d47b5a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal026c544cfb81717be117829402d47b5a)): ?>
<?php $component = $__componentOriginal026c544cfb81717be117829402d47b5a; ?>
<?php unset($__componentOriginal026c544cfb81717be117829402d47b5a); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6d73850992cd60c91283300eff9d9378)): ?>
<?php $attributes = $__attributesOriginal6d73850992cd60c91283300eff9d9378; ?>
<?php unset($__attributesOriginal6d73850992cd60c91283300eff9d9378); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6d73850992cd60c91283300eff9d9378)): ?>
<?php $component = $__componentOriginal6d73850992cd60c91283300eff9d9378; ?>
<?php unset($__componentOriginal6d73850992cd60c91283300eff9d9378); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\sanjay-sales\resources\views/admin/videos/form.blade.php ENDPATH**/ ?>
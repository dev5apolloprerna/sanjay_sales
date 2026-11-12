<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> – Sanjay Sales Admin</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>?v=1">
</head>
<body>
<?php
    $me = auth('admin')->user();
    $nav = [
        ['Main'],
        ['admin.dashboard', 'Dashboard', 'dash', 'admin.dashboard'],
        ['Catalogue'],
        ['admin.categories.index', 'Categories', 'cat', 'admin.categories.*'],
        ['admin.subcategories.index', 'Subcategories', 'sub', 'admin.subcategories.*'],
        ['admin.products.index', 'Products', 'box', 'admin.products.*'],
        ['Media & Content'],
        ['admin.gallery.index', 'Gallery', 'img', 'admin.gallery.*'],
        ['admin.clients.index', 'Our Clients', 'clients', 'admin.clients.*'],
        ['admin.videos.index', 'Video Gallery', 'video', 'admin.videos.*'],
        ['admin.blogs.index', 'Blogs', 'blog', 'admin.blogs.*'],
        /*['Account'],
        ['admin.profile', 'My Profile', 'user', 'admin.profile*'],
        ['admin.password', 'Change Password', 'lock', 'admin.password*'],*/
    ];
?>
<div class="shell">
    <aside class="side">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand"><img src="<?php echo e(asset('images/logo.png')); ?>" alt="Sanjay Sales – Welding & Cutting Related Products"></a>
        <nav class="nav">
            <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($n) === 1): ?>
                    <div class="group"><?php echo e($n[0]); ?></div>
                <?php else: ?>
                    <a href="<?php echo e(route($n[0])); ?>" class="<?php echo e(request()->routeIs($n[3]) ? 'active' : ''); ?>"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $n[2]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($n[2])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><?php echo e($n[1]); ?></a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <form method="POST" action="<?php echo e(route('admin.logout')); ?>"><?php echo csrf_field(); ?><button><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'out']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'out']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>Logout</button></form>
        </nav>
    </aside>
    <div class="overlay"></div>
    <div class="main">
        <header class="topbar">
            <button class="burger" type="button"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'menu','width' => '22','height' => '22']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu','width' => '22','height' => '22']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></button>
            <div class="title"><?php echo $__env->yieldContent('title'); ?></div>
            <div class="user">
                <button type="button">
                    <div class="avatar"><?php if($me->avatar_url): ?><img src="<?php echo e($me->avatar_url); ?>" alt=""><?php else: ?><?php echo e(strtoupper($me->initials)); ?><?php endif; ?></div>
                    <div style="text-align:left;line-height:1.2"><b style="font-size:13px"><?php echo e($me->name); ?></b><br><small class="muted"><?php echo e($me->email); ?></small></div>
                </button>
                <div class="menu" hidden>
                    <a href="<?php echo e(route('admin.profile')); ?>">My Profile</a>
                    <a href="<?php echo e(route('admin.password')); ?>">Change Password</a>
                    <form method="POST" action="<?php echo e(route('admin.logout')); ?>"><?php echo csrf_field(); ?><button style="color:var(--danger)">Logout</button></form>
                </div>
            </div>
        </header>
        <main class="content"><?php echo $__env->yieldContent('content'); ?></main>
    </div>
</div>


<div class="toasts">
    <?php if(session('success')): ?><div class="toast"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="toast error"><?php echo e(session('error')); ?></div><?php endif; ?>
</div>


<div class="modal-bg" id="confirm-modal" hidden>
    <div class="modal sm">
        <div class="modal-head"><h3>Please confirm</h3><button class="icon-btn" type="button" data-close-modal><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></button></div>
        <div class="modal-body"><p id="confirm-text" style="margin:0"></p></div>
        <div class="modal-foot"><button class="btn ghost" type="button" data-close-modal>Cancel</button><button class="btn danger" type="button" id="confirm-yes">Delete</button></div>
    </div>
</div>
<script src="<?php echo e(asset('js/admin.js')); ?>?v=1"></script>
</body>
</html>
<?php /**PATH C:\wamp64\www\sanjay-sales\resources\views/admin/layout.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', $cosmetic->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto mt-10 px-6 text-center bg-white shadow-lg rounded-2xl p-8">
    <!-- Imagem -->
    <div class="flex justify-center">
        <img 
            src="<?php echo e($cosmetic->image); ?>" 
            alt="<?php echo e($cosmetic->name); ?>" 
            class="w-64 h-64 object-contain mb-6 drop-shadow-md transition-transform duration-300 hover:scale-105"
        >
    </div>

    <!-- Título -->
    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
        <?php echo e($cosmetic->name); ?>

    </h1>

    <!-- Subinformações -->
    <div class="flex flex-col items-center space-y-2 text-gray-600 text-base mb-6">
        <p><span class="font-semibold text-gray-800">Tipo:</span> <?php echo e($cosmetic->type); ?></p>
        <p><span class="font-semibold text-gray-800">Raridade:</span> <?php echo e($cosmetic->rarity); ?></p>
        <p><span class="font-semibold text-gray-800">Preço:</span> <?php echo e($cosmetic->price); ?> V-Bucks</p>
        <p><span class="font-semibold text-gray-800">Data de inclusão:</span> 
            <?php echo e(\Carbon\Carbon::parse($cosmetic->release_date)->format('d/m/Y')); ?>

        </p>
    </div>

    <!-- Badges -->
    <div class="flex justify-center gap-3 mb-8">
        <?php if($cosmetic->is_new): ?>
            <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">Novo</span>
        <?php endif; ?>
        <?php if($cosmetic->is_shop): ?>
            <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">À venda</span>
        <?php endif; ?>
    </div>

    <!-- Botão Voltar -->
    <a href="<?php echo e(route('cosmetics.index')); ?>" 
       class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg shadow-sm transition duration-200">
        ← Voltar à lista
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/cosmetics/show.blade.php ENDPATH**/ ?>
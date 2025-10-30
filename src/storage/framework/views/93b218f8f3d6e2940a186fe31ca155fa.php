

<?php $__env->startSection('title', 'Cosméticos Fortnite'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto mt-6 px-4">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">🎮 Cosméticos Fortnite</h2>

    <!-- Aqui você itera sobre vários -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php $__currentLoopData = $cosmetics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden">
<?php if(!empty($item->image)): ?>
    <img 
        src="<?php echo e($item->image); ?>" 
        alt="<?php echo e($item->name); ?>" 
        class="w-full h-48 object-contain bg-gray-100 rounded-t-xl transition-transform duration-300 hover:scale-105"
        onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'flex flex-col items-center justify-center w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 text-gray-500 font-medium rounded-t-xl\'><svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-10 h-10 mb-1 text-gray-400\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M3 3v18h18V3H3zm3 3h12v12H6V6zm6 3a3 3 0 110 6 3 3 0 010-6z\' /></svg><span>Imagem não disponível</span></div>'"
    >
<?php else: ?>
    <div class="flex flex-col items-center justify-center w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 text-gray-500 font-medium rounded-t-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3v18h18V3H3zm3 3h12v12H6V6zm6 3a3 3 0 110 6 3 3 0 010-6z" />
        </svg>
        <span>Imagem não disponível</span>
    </div>
<?php endif; ?>

                <div class="p-4 text-center">
                    <h6 class="text-lg font-semibold text-gray-800 truncate"><?php echo e($item->name); ?></h6>
                    <p class="text-sm text-gray-500"><?php echo e($item->type); ?> • <?php echo e($item->rarity); ?></p>
                    <p class="mt-2 text-blue-600 font-bold"><?php echo e($item->price); ?> V-Bucks</p>

                    <a href="<?php echo e(route('cosmetics.show', $item->id)); ?>" 
                       class="inline-block mt-3 bg-transparent border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition duration-200">
                        Ver detalhes
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Paginação -->
    <div class="mt-8">
        <?php echo e($cosmetics->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/cosmetics/index.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Fort Cosmetics'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?php echo e(url('/')); ?>" class="text-xl font-bold text-blue-600">
                Fort<span class="text-gray-700">Cosmetics</span>
            </a>

            <div class="flex items-center gap-4">
                <a href="#" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                <a href="#" class="text-gray-600 hover:text-blue-600">Produtos</a>
                <a href="#" class="text-gray-600 hover:text-blue-600">Sobre</a>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto px-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="mt-12 border-t py-4 text-center text-sm text-gray-500">
        © <?php echo e(date('Y')); ?> Fort Cosmetics. Todos os direitos reservados.
    </footer>

</body>
</html>
<?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>
<header class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="page-header">
        {{-- Breadcrumb Navigation --}}
        @include('filament.components.breadcrumbs')

        {{-- Page Title --}}
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
        {{ __('Table') }} <!-- Dynamic title, can be translated -->
        </h1>
    </div>
</header>

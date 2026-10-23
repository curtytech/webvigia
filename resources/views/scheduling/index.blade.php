<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Agendamentos | WebVigia</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-4 sm:px-6 lg:px-8">
            <header class="flex items-center justify-between border-b border-slate-200 py-5">
                <a href="/" class="flex items-center gap-3 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <svg aria-hidden="true" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 3v3m8-3v3M4.5 9h15M6 5.5h12A1.5 1.5 0 0 1 19.5 7v12A1.5 1.5 0 0 1 18 20.5H6A1.5 1.5 0 0 1 4.5 19V7A1.5 1.5 0 0 1 6 5.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 14 2 2 4-4" />
                        </svg>
                    </span>
                    <span class="text-lg font-semibold tracking-tight">WebVigia</span>
                </a>

                <a href="/admin" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                    Painel administrativo
                </a>
            </header>

            <main class="flex flex-1 flex-col py-12 sm:py-16">
                <div class="mx-auto w-full max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-wider text-blue-700">Controle de acesso</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Agendamentos</h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                        Consulte e organize as visitas programadas com praticidade e segurança.
                    </p>

                    <section aria-labelledby="scheduling-panel-title" class="mt-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                            <svg aria-hidden="true" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 3v3m8-3v3M4.5 9h15M6 5.5h12A1.5 1.5 0 0 1 19.5 7v12A1.5 1.5 0 0 1 18 20.5H6A1.5 1.5 0 0 1 4.5 19V7A1.5 1.5 0 0 1 6 5.5Z" />
                            </svg>
                        </div>

                        <h2 id="scheduling-panel-title" class="mt-5 text-xl font-semibold text-slate-900">
                            Gerencie as visitas pelo painel
                        </h2>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-slate-600">
                            No painel administrativo, você pode consultar os agendamentos, acompanhar seus status e manter os dados das visitas atualizados.
                        </p>

                        <a href="/admin/schedulings" class="mt-6 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                            Acessar agendamentos
                            <svg aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M7 7h10v10" />
                            </svg>
                        </a>
                    </section>
                </div>
            </main>

            <footer class="border-t border-slate-200 py-5 text-center text-sm text-slate-500">
                WebVigia &mdash; Controle de acesso
            </footer>
        </div>
    </body>
</html>

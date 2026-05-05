<div class="p-6 text-gray-900">
    {{ __("You're logged in!") }}

    @if(auth()->user()->role == 'super_admin')
        <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-md">
            <h3 class="text-lg font-bold text-red-700">Mode Super Admin Aktif</h3>
            <p class="text-sm text-red-600 mb-4">Anda memiliki akses penuh ke Service Center dan Management User.</p>
            
            <a href="{{ url('/hq-admin/dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 transition ease-in-out duration-150">
                Masuk ke Pusat Komando (HQ)
            </a>
        </div>
    @endif
</div>
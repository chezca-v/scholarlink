<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button" 
            class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#D94848] hover:bg-[#b91c1c] shadow-md transition-colors border border-[#DC2626]"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="relative bg-white rounded-[20px] overflow-hidden">
            @csrf
            @method('delete')

            <div class="bg-[#FEF2F2] border-b border-[#FECACA] px-8 py-5 text-center">
                <h2 class="font-display font-bold text-[22px] text-[#DC2626]">
                    Delete this account?
                </h2>
            </div>

            <div class="p-8">
                <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-xl p-4 mb-6">
                    <p class="text-[13px] text-[#991B1B] leading-relaxed">
                        You're about to permanently delete your <strong class="font-bold">ScholarLink Account</strong>. This will also remove all associated applications and cannot be undone.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2">Type <strong class="text-[#DC2626]">DELETE</strong> to confirm</label>
                    <input type="text" name="confirm_text" placeholder="DELETE" required
                           class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-[13px] text-slate-700 focus:outline-none focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                    
                    <div class="mt-4">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2">Password</label>
                        <input id="password" name="password" type="password" required
                               class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-[13px] text-slate-700 focus:outline-none focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all"
                               placeholder="{{ __('Password') }}">
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-[12px] text-red-500" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" 
                            class="px-6 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white min-w-[100px]">
                        Keep it
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl text-[13px] font-semibold text-[#D94848] bg-[#FEF2F2] hover:bg-[#FEE2E2] shadow-sm transition-colors border border-[#FECACA] min-w-[120px]">
                        Yes, delete
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
</section>

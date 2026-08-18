<x-admin-layout>
    <div x-data="userManage">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">User Management</h1>
                <p class="text-sm text-muted-foreground mt-1">Manage admin accounts and their access levels.</p>
            </div>

            <button @click="showAddModal = true"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-accent to-gold-dark text-primary px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-[.1em] shadow-lg shadow-accent/20 hover:shadow-xl hover:shadow-accent/30 hover:scale-[1.02] active:scale-[0.97] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><line x1="19" y1="8" x2="19" y2="14" /><line x1="16" y1="11" x2="22" y2="11" /></svg>
                Add New User
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-black/[.04]">
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">User</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Role</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Status</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Joined</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[.03]">
                        @forelse($users as $user)
                            <tr class="hover:bg-[#F5F7FA]/60 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center text-white font-black text-xs shadow-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-primary text-[13px]">{{ $user->name }}</p>
                                            <p class="text-[10px] text-muted-foreground/50 font-medium">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $roleBadge = match($user->role) {
                                            'super_admin' => 'bg-purple-50 text-purple-600 border-purple-200/80',
                                            'soccom' => 'bg-amber-50 text-amber-600 border-amber-200/80',
                                            default => 'bg-blue-50 text-blue-600 border-blue-200/80',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-[.1em] border {{ $roleBadge }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60 mr-1.5"></span>
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $user->is_active ? 'text-emerald-500' : 'text-red-400' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                                        {{ $user->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-muted-foreground/50">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="editUser({{ json_encode($user) }})" class="p-2 rounded-xl hover:bg-[#F5F7FA] text-muted-foreground/40 hover:text-primary transition-all" title="Edit User">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" /></svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    @click="$store.confirm.open({ title: 'Delete User', message: 'Are you sure you want to permanently delete this user account? This action cannot be undone.', onConfirm: () => document.getElementById('delete-user-{{ $user->id }}').submit() })"
                                                    class="p-2 rounded-xl hover:bg-red-50 text-muted-foreground/40 hover:text-red-500 transition-all" title="Delete User">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-admin-empty title="No users found" description="Create the first admin user to get started." icon="inbox" :colSpan="5" />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add User Modal --}}
        <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white w-full max-w-md rounded-2xl border border-black/[.06] shadow-2xl overflow-hidden">
                <div class="px-6 py-5 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white flex items-center justify-between">
                    <h3 class="text-lg font-bold text-primary font-heading">Add New User</h3>
                    <button @click="showAddModal = false" class="p-1.5 rounded-lg hover:bg-black/[.04] text-muted-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18" /><path d="m6 6 12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Full Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Email Address <span class="text-red-400">*</span></label>
                        <input type="email" name="email" required class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Role <span class="text-red-400">*</span></label>
                        <select name="role" required class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                            <option value="staff">Staff (Intentions Only)</option>
                            <option value="soccom">SocCom (Events/Gallery/Schedules)</option>
                            <option value="super_admin">Super Admin (All Access)</option>
                        </select>
                        @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Password <span class="text-red-400">*</span></label>
                        <input type="password" name="password" required minlength="8" class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showAddModal = false" class="flex-1 px-4 py-2.5 rounded-xl font-bold text-sm bg-[#F5F7FA] text-muted-foreground hover:bg-black/[.04] transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl font-bold text-sm bg-primary text-primary-foreground shadow-lg shadow-primary/15 hover:shadow-xl transition-all">Create User</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit User Modal --}}
        <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white w-full max-w-md rounded-2xl border border-black/[.06] shadow-2xl overflow-hidden">
                <div class="px-6 py-5 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white flex items-center justify-between">
                    <h3 class="text-lg font-bold text-primary font-heading">Edit User</h3>
                    <button @click="showEditModal = false" class="p-1.5 rounded-lg hover:bg-black/[.04] text-muted-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18" /><path d="m6 6 12 12" /></svg>
                    </button>
                </div>
                <form x-ref="editForm" :action="`{{ route('admin.users.update', ':id') }}`.replace(':id', currentUser.id)" @submit.prevent="submitEdit()" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Full Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" x-model="currentUser.name" required class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Email Address <span class="text-red-400">*</span></label>
                        <input type="email" name="email" x-model="currentUser.email" required class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Role <span class="text-red-400">*</span></label>
                        <select name="role" x-model="currentUser.role" required class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                            <option value="staff">Staff (Intentions Only)</option>
                            <option value="soccom">SocCom (Events/Gallery/Schedules/Inquiries/Chats)</option>
                            <option value="super_admin">Super Admin (All Access)</option>
                        </select>
                        <template x-if="currentUser.role !== originalRole">
                            <p class="text-[10px] text-amber-500 mt-1 font-bold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" /><path d="M12 9v4" /><path d="M12 17h.01" /></svg>
                                Role will be changed on save
                            </p>
                        </template>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Password (Leave blank to keep current)</label>
                        <input type="password" name="password" minlength="8" class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                    </div>
                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" name="is_active" id="is_active" :checked="currentUser.is_active" value="1" class="rounded border-border text-primary focus:ring-primary">
                        <label for="is_active" class="text-xs font-bold text-primary">Account Active</label>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showEditModal = false" class="flex-1 px-4 py-2.5 rounded-xl font-bold text-sm bg-[#F5F7FA] text-muted-foreground hover:bg-black/[.04] transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl font-bold text-sm bg-primary text-primary-foreground shadow-lg shadow-primary/15 hover:shadow-xl transition-all">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

<x-admin-layout>
    <div x-data="userManage">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">User Management</h1>
                <p class="text-sm text-muted-foreground mt-1">Manage admin accounts and their access levels.</p>
            </div>

            <button @click="showAddModal = true"
                class="inline-flex items-center gap-2 bg-accent text-accent-foreground px-4 py-2 rounded-md font-bold text-sm shadow-md hover:opacity-90 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
                Add New User
            </button>
        </div>

        <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($users as $user)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-accent flex items-center justify-center text-accent-foreground font-black border-2 border-white shadow-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-primary">{{ $user->name }}</p>
                                            <p class="text-xs text-muted-foreground">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $roleBadge = match($user->role) {
                                            'super_admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'soccom' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            default => 'bg-primary/10 text-primary border-primary/20',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $roleBadge }}">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-1.5 text-xs font-bold {{ $user->is_active ? 'text-green-600' : 'text-red-500' }}">
                                        <span class="h-2 w-2 rounded-full {{ $user->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                        {{ $user->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-muted-foreground">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editUser({{ json_encode($user) }})" class="p-2 hover:bg-muted rounded-md text-muted-foreground transition-colors" title="Edit User">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    @click="$store.confirm.open({
                                                        title: 'Delete User',
                                                        message: 'Are you sure you want to permanently delete this user account? This action cannot be undone.',
                                                        onConfirm: () => document.getElementById('delete-user-{{ $user->id }}').submit()
                                                    })"
                                                    class="p-2 hover:bg-red-50 rounded-md text-red-500 transition-colors" title="Delete User">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
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
        <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-card w-full max-w-md rounded-2xl border shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                <div class="p-6 border-b bg-muted/30 flex items-center justify-between">
                    <h3 class="text-xl font-black text-primary uppercase tracking-tighter italic font-heading">Add New User</h3>
                    <button @click="showAddModal = false" class="text-muted-foreground hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Full Name <span class="text-destructive">*</span></label>
                        <input type="text" name="name" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('name') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Email Address <span class="text-destructive">*</span></label>
                        <input type="email" name="email" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('email') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Role <span class="text-destructive">*</span></label>
                        <select name="role" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                            <option value="staff">Staff (Intentions Only)</option>
                            <option value="soccom">SocCom (Events/Gallery/Schedules)</option>
                            <option value="super_admin">Super Admin (All Access)</option>
                        </select>
                        @error('role') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Password <span class="text-destructive">*</span></label>
                        <input type="password" name="password" required minlength="8" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('password') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showAddModal = false" class="flex-1 px-4 py-2 rounded-lg font-bold text-sm bg-muted text-muted-foreground hover:bg-muted/80 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg font-black text-sm bg-accent text-accent-foreground shadow-md hover:scale-[1.02] transition-all">Create User</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit User Modal --}}
        <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-card w-full max-w-md rounded-2xl border shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                <div class="p-6 border-b bg-muted/30 flex items-center justify-between">
                    <h3 class="text-xl font-black text-primary uppercase tracking-tighter italic font-heading">Edit User</h3>
                    <button @click="showEditModal = false" class="text-muted-foreground hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <form x-ref="editForm" :action="`/admin-portal/users/${currentUser.id}`" @submit.prevent="submitEdit()" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Full Name <span class="text-destructive">*</span></label>
                        <input type="text" name="name" x-model="currentUser.name" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Email Address <span class="text-destructive">*</span></label>
                        <input type="email" name="email" x-model="currentUser.email" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Role <span class="text-destructive">*</span></label>
                        <select name="role" x-model="currentUser.role" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                            <option value="staff">Staff (Intentions Only)</option>
                            <option value="soccom">SocCom (Events/Gallery/Schedules/Inquiries/Chats)</option>
                            <option value="super_admin">Super Admin (All Access)</option>
                        </select>
                        <template x-if="currentUser.role !== originalRole">
                            <p class="text-[10px] text-amber-600 mt-1 font-bold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                Role will be changed on save
                            </p>
                        </template>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Password (Leave blank to keep current)</label>
                        <input type="password" name="password" minlength="8" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" name="is_active" id="is_active" :checked="currentUser.is_active" value="1" class="rounded border-border text-accent focus:ring-accent">
                        <label for="is_active" class="text-xs font-bold text-primary">Account Active</label>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showEditModal = false" class="flex-1 px-4 py-2 rounded-lg font-bold text-sm bg-muted text-muted-foreground hover:bg-muted/80 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg font-black text-sm bg-accent text-accent-foreground shadow-md hover:scale-[1.02] transition-all">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

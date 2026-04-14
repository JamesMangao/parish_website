<x-admin-layout>
    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false,
        currentUser: {},
        editUser(user) {
            this.currentUser = { ...user };
            this.showEditModal = true;
        }
    }">
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

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg text-sm font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg text-sm font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-card rounded-2xl border shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-muted/30">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">User</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">Role</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">Joined</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($users as $user)
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
                                <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'soccom' ? 'bg-amber-100 text-amber-700' : 'bg-primary/10 text-primary') }}">
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
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 hover:bg-red-50 rounded-md text-red-500 transition-colors" title="Delete User">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Add User Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm" x-cloak>
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
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Full Name</label>
                        <input type="text" name="name" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Role</label>
                        <select name="role" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                            <option value="staff">Staff (Intentions Only)</option>
                            <option value="soccom">SocCom (Events/Gallery/Schedules)</option>
                            <option value="super_admin">Super Admin (All Access)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Password</label>
                        <input type="password" name="password" required minlength="8" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showAddModal = false" class="flex-1 px-4 py-2 rounded-lg font-bold text-sm bg-muted text-muted-foreground hover:bg-muted/80 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg font-black text-sm bg-accent text-accent-foreground shadow-md hover:scale-[1.02] transition-all">Create User</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm" x-cloak>
            <div class="bg-card w-full max-w-md rounded-2xl border shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                <div class="p-6 border-b bg-muted/30 flex items-center justify-between">
                    <h3 class="text-xl font-black text-primary uppercase tracking-tighter italic font-heading">Edit User</h3>
                    <button @click="showEditModal = false" class="text-muted-foreground hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <form :action="`/admin/users/${currentUser.id}`" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Full Name</label>
                        <input type="text" name="name" x-model="currentUser.name" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Email Address</label>
                        <input type="email" name="email" x-model="currentUser.email" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Role</label>
                        <select name="role" x-model="currentUser.role" required class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                            <option value="staff">Staff (Intentions Only)</option>
                            <option value="soccom">SocCom (Events/Gallery/Schedules/Inquiries/Chats)</option>
                            <option value="super_admin">Super Admin (All Access)</option>
                        </select>
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

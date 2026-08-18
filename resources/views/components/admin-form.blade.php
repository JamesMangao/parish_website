<x-admin-layout>
    <div class="mb-8">
        <a href="{{ $backRoute }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/60 hover:text-primary transition-colors mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Back to List
        </a>
        <h1 class="font-heading text-3xl font-bold text-primary italic">{{ $title }}</h1>
        <p class="text-sm text-muted-foreground mt-1.5">{{ $description }}</p>
    </div>

    <div class="max-w-4xl bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] p-8"
         x-data="{
             submitting: false,
             async handleSubmit(event) {
                 event.preventDefault();
                 this.submitting = true;
                 const form = event.target;
                 const formData = new FormData(form);

                 try {
                     const response = await fetch(form.action, {
                         method: form.method === 'GET' ? 'GET' : 'POST',
                         body: formData,
                         headers: {
                             'X-Requested-With': 'XMLHttpRequest',
                             'Accept': 'application/json',
                         },
                     });

                     if (response.ok) {
                         const data = await response.json();
                         $store.toast.trigger(data.message || '{{ addslashes($submitLabel ?? "Saved") }} successfully!', 'success');
                         setTimeout(() => {
                             window.location.href = '{{ $backRoute }}';
                         }, 800);
                     } else {
                         const data = await response.json();
                         if (data.errors) {
                             const msgs = Object.values(data.errors).flat().join('\n');
                             $store.toast.trigger(msgs || 'Validation failed. Please check your inputs.', 'error');
                         } else {
                             $store.toast.trigger(data.message || 'Something went wrong.', 'error');
                         }
                         this.submitting = false;
                     }
                 } catch (e) {
                     $store.toast.trigger('Network error. Please try again.', 'error');
                     this.submitting = false;
                 }
             }
         }">
        <form action="{{ $action }}" method="POST"
              class="space-y-6"
              @submit.prevent="handleSubmit($event)"
              enctype="{{ $enctype ?? 'application/x-www-form-urlencoded' }}">
            @csrf
            @if($method ?? false)
                @method($method)
            @endif

            {{ $slot }}

            <div class="pt-4 flex items-center gap-4 border-t border-black/[.04]">
                <button type="submit"
                    :disabled="submitting"
                    class="bg-primary text-primary-foreground px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/15 transition-all duration-200 flex items-center gap-2"
                    :class="submitting ? 'opacity-60 cursor-wait' : 'hover:shadow-xl hover:shadow-primary/20 hover:scale-[1.01] active:scale-[0.98]'">
                    <svg x-show="submitting" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="submitting ? 'Saving...' : '{{ addslashes($submitLabel ?? "Save Changes") }}'"></span>
                </button>
                <a href="{{ $backRoute }}"
                    class="text-sm font-bold text-muted-foreground/60 hover:text-primary transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>

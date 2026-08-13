@php
    $predefined = \App\Models\Announcement::PREDEFINED_CATEGORIES;
    $storedCategory = $storedCategory ?? 'Parish Life';

    if (old('category') !== null) {
        $selectedCategory = old('category');
        $customCategoryValue = old('custom_category', '');
    } else {
        $isCustom = ! in_array($storedCategory, $predefined, true);
        $selectedCategory = $isCustom ? \App\Models\Announcement::CATEGORY_OTHER : $storedCategory;
        $customCategoryValue = $isCustom ? $storedCategory : '';
    }
@endphp

<div x-data="{ category: @js($selectedCategory) }">
    <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Category</label>
    <select name="category" x-model="category"
        class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary">
        @foreach ($predefined as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
        <option value="{{ \App\Models\Announcement::CATEGORY_OTHER }}">Other</option>
    </select>
    @error('category') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror

    <div x-show="category === @js(\App\Models\Announcement::CATEGORY_OTHER)" x-transition class="mt-3">
        <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Custom Category</label>
        <input type="text" name="custom_category" value="{{ $customCategoryValue }}"
            :required="category === @js(\App\Models\Announcement::CATEGORY_OTHER)"
            class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary"
            placeholder="e.g., Youth Ministry, Social Action">
        <p class="text-xs text-muted-foreground mt-1">Enter a short label shown on the public announcement page.</p>
        @error('custom_category') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
    </div>
</div>

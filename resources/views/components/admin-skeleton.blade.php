@props(['type' => 'table', 'rows' => 5])

@if($type === 'table')
    <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 border-b">
                    <tr>
                        @for($i = 0; $i < $rows; $i++)
                            <th class="px-6 py-4"><div class="h-3 rounded bg-muted animate-pulse" style="width: {{ rand(40,80) }}%"></div></th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @for($r = 0; $r < 5; $r++)
                        <tr>
                            @for($c = 0; $c < $rows; $c++)
                                <td class="px-6 py-4"><div class="h-3 rounded bg-muted/60 animate-pulse" style="width: {{ rand(50,90) }}%"></div></td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@elseif($type === 'card')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @for($i = 0; $i < 3; $i++)
            <div class="bg-card rounded-xl border shadow-sm p-6 space-y-3">
                <div class="h-4 rounded bg-muted animate-pulse w-1/3"></div>
                <div class="h-8 rounded bg-muted animate-pulse w-1/2"></div>
                <div class="h-3 rounded bg-muted/60 animate-pulse w-2/3"></div>
            </div>
        @endfor
    </div>
@elseif($type === 'stats')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-card rounded-xl border shadow-sm p-6 space-y-3">
                <div class="h-3 rounded bg-muted animate-pulse w-1/2"></div>
                <div class="h-8 rounded bg-muted animate-pulse w-1/3"></div>
            </div>
        @endfor
    </div>
@endif

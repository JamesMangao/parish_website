<x-admin-layout>
    <script>
        function pptAutomation() {
            return {
                showPreview: false,
                editMode: false,
                currentSlide: 0,
                previewData: { date: '', slides: [] },
                
                async fetchPreview() {
                    try {
                        const response = await fetch('/admin/preview-ppt');
                        if (!response.ok) throw new Error('Failed to fetch');
                        const data = await response.json();
                        this.previewData = data;
                        this.editMode = false;
                        this.currentSlide = 0;
                        this.showPreview = true;
                    } catch (error) {
                        this.showNotification('Error loading preview. Please ensure there are approved intentions.', 'error');
                    }
                },
                async generateFinal() {
                    try {
                        const response = await fetch('/admin/generate-ppt', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ slides: this.previewData.slides })
                        });

                        if (!response.ok) throw new Error('Generation failed');

                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `Mass_Intentions_${new Date().toISOString().split('T')[0]}.pptx`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        this.showNotification('PowerPoint presentation generated successfully!', 'success');
                    } catch (error) {
                        this.showNotification('Error generating PPT. Please try again.', 'error');
                    }
                },
                handleKeyDown(e) {
                    if (!this.editMode || !this.showPreview) return;

                    const slide = this.previewData.slides[this.currentSlide];
                    if (!slide) return;

                    const step = e.shiftKey ? 10 : 2;

                    if (e.key === 'ArrowUp') {
                        slide.offsetY -= step;
                        e.preventDefault();
                    } else if (e.key === 'ArrowDown') {
                        slide.offsetY += step;
                        e.preventDefault();
                    } else if (e.key === 'ArrowLeft') {
                        slide.offsetX -= step;
                        e.preventDefault();
                    } else if (e.key === 'ArrowRight') {
                        slide.offsetX += step;
                        e.preventDefault();
                    }
                },
                dragging: false,
                startX: 0,
                startY: 0,
                initialOffsetX: 0,
                initialOffsetY: 0,
                startDrag(e) {
                    if (!this.editMode) return;
                    this.dragging = true;
                    this.startX = e.clientX;
                    this.startY = e.clientY;
                    this.initialOffsetX = this.previewData.slides[this.currentSlide].offsetX;
                    this.initialOffsetY = this.previewData.slides[this.currentSlide].offsetY;
                },
                onDrag(e) {
                    if (!this.dragging) return;
                    const dx = e.clientX - this.startX;
                    const dy = e.clientY - this.startY;
                    this.previewData.slides[this.currentSlide].offsetX = this.initialOffsetX + (dx * 2);
                    this.previewData.slides[this.currentSlide].offsetY = this.initialOffsetY + (dy * 2);
                },
                stopDrag() {
                    this.dragging = false;
                },
                creatingSlides: false,
                async createGoogleSlides() {
                    if (this.creatingSlides) return;
                    this.creatingSlides = true;

                    try {
                        const response = await fetch('/admin/create-google-slides', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                slides: this.previewData.slides,
                                date: this.previewData.date
                            })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            this.showNotification(data.message || 'Failed to create slides', 'error');
                            return;
                        }

                        if (data.success && data.url) {
                            this.showNotification('Google Slide has been successfully created/updated!', 'success');
                            window.open(data.url, '_blank');
                        }
                    } catch (error) {
                        this.showNotification('Error: ' + error.message, 'error');
                    } finally {
                        this.creatingSlides = false;
                    }
                }
            }
        }
    </script>
    <div x-data="pptAutomation()" @keydown.window="handleKeyDown($event)" @mousemove.window="onDrag($event)"
        @mouseup.window="stopDrag()">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">Dashboard</h1>
                <p class="text-sm text-muted-foreground mt-1">Overview of parish activities and social communications
                    automation.</p>
            </div>

            <div class="flex gap-3">
                <button @click="fetchPreview()"
                    class="inline-flex items-center gap-2 bg-muted border border-border px-4 py-2 rounded-md font-bold text-sm hover:bg-muted/80 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-eye">
                        <path
                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    Preview Content
                </button>
                <form action="/admin/generate-ppt" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-accent text-accent-foreground px-4 py-2 rounded-md font-bold text-sm shadow-md hover:opacity-90 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-presentation">
                            <path d="M2 3h20" />
                            <path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3" />
                            <path d="m7 21 5-5 5 5" />
                        </svg>
                        Generate PPT
                    </button>
                </form>
            </div>

            <!-- Interactive Slide Preview Modal -->
            <div x-show="showPreview"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm"
                style="display: none;" x-transition x-cloak>

                <div
                    class="bg-card w-full max-w-5xl rounded-2xl border shadow-2xl flex flex-col h-[85vh] overflow-hidden">
                    <!-- Modal Header -->
                    <div class="p-6 border-b flex items-center justify-between bg-muted/30">
                        <div>
                            <h3 class="text-xl font-black text-primary uppercase tracking-tighter italic font-heading">Draft
                                Presentation</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mt-1"
                                x-text="'Mass Date: ' + previewData.date"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click="showPreview = false"
                                class="p-2 hover:bg-muted rounded-full transition-colors text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M18 6 6 18" />
                                    <path d="m6 6 12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Slide Viewer (Carousel & Editor) -->
                    <div class="flex-1 overflow-hidden relative group p-8 flex items-center justify-center bg-muted/10">
                        <template x-for="(slide, index) in previewData.slides" :key="index">
                            <div x-show="currentSlide === index"
                                class="w-[800px] h-[500px] border-2 border-black bg-white shadow-2xl relative flex flex-col overflow-hidden"
                                x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0 scale-95 translate-x-8"
                                x-transition:enter-end="opacity-100 scale-100 translate-x-0">

                                <!-- Intro Slide Design -->
                                <template x-if="slide.type === 'intro'">
                                    <div class="flex-1 flex flex-col items-center justify-center text-center p-12 space-y-4 transition-all"
                                        :style="`transform: translate(${(slide.offsetX - 75)/2}px, ${(slide.offsetY - 100)/2}px)`">
                                        <textarea x-model="slide.mainText" :disabled="!editMode"
                                            :class="!editMode && 'cursor-default'"
                                            class="w-full text-xl font-bold text-black border-none focus:ring-0 bg-transparent text-center resize-none p-0"
                                            rows="2"></textarea>
                                        <textarea x-model="slide.boldText" :disabled="!editMode"
                                            :class="!editMode && 'cursor-default'"
                                            class="w-full text-5xl font-black text-red-600 border-none focus:ring-0 bg-transparent text-center resize-none p-0 uppercase leading-tight"
                                            rows="1"></textarea>
                                        <textarea x-model="slide.footerText" :disabled="!editMode"
                                            :class="!editMode && 'cursor-default'"
                                            class="w-full text-xl font-bold text-black border-none focus:ring-0 bg-transparent text-center resize-none p-0"
                                            rows="2"></textarea>
                                    </div>
                                </template>

                                <!-- List Slide Design -->
                                <template x-if="slide.type === 'list'">
                                    <div class="flex-1 flex flex-col">
                                        <div class="h-12 bg-black flex items-center justify-center px-4">
                                            <input type="text" x-model="slide.category" :disabled="!editMode"
                                                :class="!editMode && 'cursor-default'"
                                                class="bg-transparent border-none text-white text-center font-bold uppercase tracking-wider focus:ring-0 w-full text-sm">
                                        </div>
                                        <div class="flex-1 p-8 overflow-y-auto transition-all text-black"
                                            :style="`padding-left: ${slide.offsetX}px; padding-top: ${slide.offsetY - 60}px` ">
                                            <div class="space-y-4">
                                                <template x-for="(item, iIndex) in slide.items" :key="iIndex">
                                                    <div class="flex gap-3 items-start group/item">
                                                        <span class="text-xl font-bold text-black pt-1"
                                                            x-text="slide.isRepose ? '+' : '•'"></span>
                                                        <div class="flex-1 space-y-1">
                                                            <input type="text" x-model="item.name" :disabled="!editMode"
                                                                :class="!editMode && 'cursor-default'"
                                                                class="w-full text-lg font-black text-blue-800 border-none focus:ring-0 bg-transparent p-0 uppercase placeholder:text-gray-200">
                                                            <input type="text" x-model="item.description"
                                                                :disabled="!editMode"
                                                                :class="!editMode && 'cursor-default'"
                                                                class="w-full text-sm font-medium text-gray-500 border-none focus:ring-0 bg-transparent p-0 italic placeholder:text-gray-200">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Nav Buttons -->
                        <div class="absolute inset-y-0 left-4 flex items-center">
                            <button x-show="currentSlide > 0" @click="currentSlide--"
                                class="p-3 bg-white hover:bg-muted border rounded-xl shadow-lg transition-all text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>
                        </div>
                        <div class="absolute inset-y-0 right-4 flex items-center">
                            <button x-show="currentSlide < previewData.slides.length - 1" @click="currentSlide++"
                                class="p-3 bg-white hover:bg-muted border rounded-xl shadow-lg transition-all text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-6 border-t flex items-center justify-between bg-muted/20">
                        <div class="flex items-center gap-6 text-primary">
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">Slide</span>
                                <span class="text-sm font-bold"
                                    x-text="(currentSlide + 1) + ' / ' + previewData.slides.length"></span>
                            </div>

                            <!-- Positioning Hint -->
                            <div x-show="editMode"
                                class="flex items-center gap-4 animate-in fade-in slide-in-from-left-4 duration-300">
                                <div class="h-6 w-px bg-border"></div>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1.5">
                                        <kbd
                                            class="px-2 py-0.5 rounded border border-muted bg-white text-[10px] font-black shadow-sm">↑
                                            ↓ ← →</kbd>
                                        <span class="text-[10px] font-black text-muted-foreground uppercase">Move
                                            Text</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <kbd
                                            class="px-2 py-0.5 rounded border border-muted bg-white text-[10px] font-black shadow-sm">Shift</kbd>
                                        <span class="text-[10px] font-black text-muted-foreground uppercase">Fast
                                            Move</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button @click="showPreview = false"
                                class="px-6 py-2 rounded-lg font-bold text-sm bg-gray-100 hover:bg-gray-200 border border-gray-300 transition-all text-gray-700">Cancel</button>
                            
                            <button @click="editMode = !editMode"
                                :class="editMode ? 'bg-primary text-primary-foreground' : 'bg-muted text-primary'"
                                class="px-6 py-2 rounded-lg font-bold text-sm border transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                <span x-text="editMode ? 'Finish Editing' : 'Edit Layout'"></span>
                            </button>

                            <button @click="createGoogleSlides()" :disabled="creatingSlides"
                                class="px-6 py-2 rounded-lg font-black text-sm shadow-md hover:scale-[1.02] transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed bg-amber-500 text-white">
                                <template x-if="!creatingSlides">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" x2="8" y1="13" y2="13" />
                                        <line x1="16" x2="8" y1="17" y2="17" />
                                        <line x1="10" x2="8" y1="9" y2="9" />
                                    </svg>
                                </template>
                                <template x-if="creatingSlides">
                                    <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                    </svg>
                                </template>
                                <span x-text="creatingSlides ? 'Creating Slides...' : 'Create in Google Slides'"></span>
                            </button>
                            <button @click="generateFinal()"
                                class="px-6 py-2 bg-accent text-accent-foreground rounded-lg font-black text-sm shadow-md hover:scale-[1.02] transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" x2="12" y1="15" y2="3" />
                                </svg>
                                Download Final PPT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php $role = Auth::user()->role; @endphp

        <!-- Stats Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @if($role === 'super_admin' || $role === 'staff')
                <!-- Total Intentions -->
                <div class="bg-card rounded-xl border p-6 flex flex-col justify-between shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Total
                            Intentions</span>
                        <div class="p-2 bg-primary/10 rounded text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-file-text">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M10 9H8" />
                                <path d="M16 13H8" />
                                <path d="M16 17H8" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-primary">{{ $stats['total_intentions'] ?? 0 }}</p>
                </div>

                <!-- Pending Review -->
                <div class="bg-card rounded-xl border p-6 flex flex-col justify-between shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Pending
                            Review</span>
                        <div class="p-2 bg-accent/10 rounded text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-clock">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-accent">{{ $stats['pending_intentions'] ?? 0 }}</p>
                </div>
            @endif

            @if($role === 'super_admin' || $role === 'soccom')
                <!-- Active Schedules -->
                <div class="bg-card rounded-xl border p-6 flex flex-col justify-between shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Active
                            Schedules</span>
                        <div class="p-2 bg-purple-100 rounded text-purple-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                <line x1="16" x2="16" y1="2" y2="6" />
                                <line x1="8" x2="8" y1="2" y2="6" />
                                <line x1="3" x2="21" y1="10" y2="10" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-purple-700">{{ $stats['active_schedules'] ?? 0 }}</p>
                </div>

                <!-- Announcements -->
                <div class="bg-card rounded-xl border p-6 flex flex-col justify-between shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Announcements</span>
                        <div class="p-2 bg-blue-100 rounded text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-megaphone">
                                <path d="m3 11 18-5v12L3 14v-3z" />
                                <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-blue-700">{{ $stats['total_announcements'] ?? 0 }}</p>
                </div>
            @endif
        </div>

        <!-- Charts Section -->
        <div class="grid gap-6 md:grid-cols-2 mt-8">
            <div class="bg-card rounded-xl border p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-primary uppercase tracking-widest">Intentions Submission Trend</h3>
                    <span class="text-[10px] font-bold text-muted-foreground uppercase">Last 8 Weeks</span>
                </div>
                <div class="h-[250px]">
                    <canvas id="intentionsChart"></canvas>
                </div>
            </div>
            <div class="bg-card rounded-xl border p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-primary uppercase tracking-widest">Inquiry Distribution</h3>
                    <span class="text-[10px] font-bold text-muted-foreground uppercase">By Category</span>
                </div>
                <div class="h-[250px]">
                    <canvas id="inquiriesChart"></canvas>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Intentions Chart
                const intentionsCtx = document.getElementById('intentionsChart').getContext('2d');
                new Chart(intentionsCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($intentionsTrend->map(fn($t) => 'W' . substr($t->week, 4))) !!},
                        datasets: [{
                            label: 'Intentions',
                            data: {!! json_encode($intentionsTrend->pluck('total')) !!},
                            borderColor: '#1e3a8a',
                            backgroundColor: 'rgba(30, 58, 138, 0.05)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#1e3a8a',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });

                // Inquiries Chart
                const inquiriesCtx = document.getElementById('inquiriesChart').getContext('2d');
                new Chart(inquiriesCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($inquiryTypes->pluck('type')) !!},
                        datasets: [{
                            label: 'Inquiries',
                            data: {!! json_encode($inquiryTypes->pluck('total')) !!},
                            backgroundColor: [
                                '#1e3a8a', '#d946ef', '#10b981', '#f59e0b', '#ef4444'
                            ],
                            borderRadius: 6,
                            barThickness: 20
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            });
        </script>

        <!-- Quick Actions -->
        <div class="mt-12">
            <h2 class="font-heading text-xl font-bold text-primary mb-6">Quick Actions</h2>
            <div class="grid gap-4 md:grid-cols-3">
                @if($role === 'super_admin' || $role === 'staff')
                    <a href="/admin/intentions"
                        class="p-4 bg-card border rounded-lg hover:border-accent hover:shadow-md transition-all flex items-center gap-4">
                        <div
                            class="h-10 w-10 flex items-center justify-center rounded-full bg-accent text-accent-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-heart">
                                <path
                                    d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z" />
                            </svg>
                        </div>
                        <div class="text-sm font-bold text-primary">Review Intentions</div>
                    </a>
                @endif

                @if($role === 'super_admin' || $role === 'soccom')
                    <a href="/admin/schedules"
                        class="p-4 bg-card border rounded-lg hover:border-accent hover:shadow-md transition-all flex items-center gap-4">
                        <div class="h-10 w-10 flex items-center justify-center rounded-full bg-purple-100 text-purple-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                <line x1="16" x2="16" y1="2" y2="6" />
                                <line x1="8" x2="8" y1="2" y2="6" />
                                <line x1="3" x2="21" y1="10" y2="10" />
                            </svg>
                        </div>
                        <div class="text-sm font-bold text-primary">Manage Schedules</div>
                    </a>
                    <a href="/admin/gallery"
                        class="p-4 bg-card border rounded-lg hover:border-accent hover:shadow-md transition-all flex items-center gap-4">
                        <div class="h-10 w-10 flex items-center justify-center rounded-full bg-green-100 text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-image">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                <circle cx="9" cy="9" r="2" />
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                            </svg>
                        </div>
                        <div class="text-sm font-bold text-primary">Parish Gallery</div>
                    </a>
                    <a href="/admin/events"
                        class="p-4 bg-card border rounded-lg hover:border-accent hover:shadow-md transition-all flex items-center gap-4">
                        <div class="h-10 w-10 flex items-center justify-center rounded-full bg-amber-100 text-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-sparkles">
                                <path
                                    d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.937A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .962 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.582a.5.5 0 0 1 0 .962L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.962 0z" />
                                <path d="M20 3v4" />
                                <path d="M22 5h-4" />
                                <path d="M4 17v2" />
                                <path d="M5 18H3" />
                            </svg>
                        </div>
                        <div class="text-sm font-bold text-primary">Events Manager</div>
                    </a>
                @endif

                <a href="/" target="_blank"
                    class="p-4 bg-card border rounded-lg hover:border-accent hover:shadow-md transition-all flex items-center gap-4">
                    <div
                        class="h-10 w-10 flex items-center justify-center rounded-full bg-muted text-muted-foreground border">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-external-link">
                            <path d="M15 3h6v6" />
                            <path d="M10 14 21 3" />
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                        </svg>
                    </div>
                    <div class="text-sm font-bold text-primary">View Website</div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use App\Models\MassSchedule;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Setting;
use Illuminate\Http\Request;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Alignment;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_intentions' => MassIntention::count(),
            'pending_intentions' => MassIntention::where('status', 'pending')->count(),
            'upcoming_events' => Event::where('event_date', '>=', now())->count(),
            'total_announcements' => Announcement::count(),
            'active_schedules' => MassSchedule::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function intentions(Request $request)
    {
        $status = $request->input('status', 'all');
        $query = MassIntention::orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $intentions = $query->get();
        return view('admin.intentions', compact('intentions'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $intention = MassIntention::findOrFail($id);
        $intention->update([
            'status' => $request->input('status'),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function generatePPT(Request $request)
    {
        $slidesData = $request->input('slides');

        if (!$slidesData) {
            $preview = $this->previewPPT();
            $slidesData = json_decode($preview->getContent(), true)['slides'];
        }

        $objPHPPresentation = new PhpPresentation();

        foreach ($slidesData as $s) {
            $slide = $objPHPPresentation->createSlide();
            $bg = new \PhpOffice\PhpPresentation\Slide\Background\Color();
            $bg->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));
            $slide->setBackground($bg);

            if ($s['type'] === 'intro') {
                $slide->createLineShape(20, 20, 930, 20)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(20, 700, 930, 700)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(20, 20, 20, 700)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(930, 20, 930, 700)->getBorder()->getColor()->setARGB('FF000000');

                $introText = $slide->createRichTextShape();
                $introText->setHeight(300)->setWidth(800)
                    ->setOffsetX($s['offsetX'] ?? 75)
                    ->setOffsetY($s['offsetY'] ?? 100);
                $introText->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $textRun = $introText->createTextRun($s['mainText'] . "\n");
                $textRun->getFont()->setBold(true)->setSize(36)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'));

                $textRun = $introText->createTextRun($s['boldText'] . "\n");
                $textRun->getFont()->setBold(true)->setSize(72)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFF0000'));

                $textRun = $introText->createTextRun($s['footerText']);
                $textRun->getFont()->setBold(true)->setSize(36)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'));
            } else {
                // List Slide Styling
                $headerBar = $slide->createRichTextShape();
                $headerBar->setHeight(50)->setWidth(950)->setOffsetX(0)->setOffsetY(0);
                $headerBar->setFill((new \PhpOffice\PhpPresentation\Style\Fill())->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000')));
                $headerBar->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                $titleText = $s['category'] ?? 'INTENTIONS';
                $title = $headerBar->createTextRun($titleText);
                $title->getFont()->setBold(true)->setSize(24)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));

                $bodyShape = $slide->createRichTextShape();
                $bodyShape->setHeight(600)->setWidth(850)
                    ->setOffsetX($s['offsetX'] ?? 50)
                    ->setOffsetY(($s['offsetY'] ?? 90) + 10);

                foreach ($s['items'] as $item) {
                    $para = $bodyShape->createParagraph();
                    $bullet = $s['isRepose'] ? '+ ' : '• ';
                    
                    // Name in Blue & Bold
                    $nameRun = $para->createTextRun($bullet . strtoupper($item['name']));
                    $nameRun->getFont()->setBold(true)->setSize(22)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000080')); // Dark Blue

                    if (isset($item['description']) && $item['description']) {
                        $descRun = $para->createBreak();
                        $descRun = $para->createTextRun('  (' . $item['description'] . ')');
                        $descRun->getFont()->setSize(16)->setItalic(true)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF666666'));
                    }
                }
            }
        }

        $objPHPPresentation->removeSlideByIndex(0);
        $filename = 'Mass_Intentions_' . now()->format('Y-m-d') . '.pptx';
        $writer = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');

        header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function previewPPT()
    {
        $today = now()->startOfDay();
        $targetDate = $today;

        // 1. Check for TODAY'S Approved Intentions
        $existsToday = MassIntention::where('status', 'approved')
            ->whereDate('preferred_date', $today)
            ->exists();

        if (!$existsToday) {
            // 2. If NO approved intentions for TODAY, find the EARLIEST available future date
            $earliestFuture = MassIntention::where('status', 'approved')
                ->where('preferred_date', '>', $today)
                ->min('preferred_date');

            if ($earliestFuture) {
                // If min() returns a string date, ensure it's handled correctly
                $targetDate = is_string($earliestFuture) ? \Carbon\Carbon::parse($earliestFuture) : $earliestFuture;
            }
        }

        // 3. Get ALL approved intentions for the TARGET DATE (unlimited)
        $intentions = MassIntention::where('status', 'approved')
            ->whereDate('preferred_date', $targetDate)
            ->get()
            ->groupBy('intention_type');

        $displayDate = ($targetDate instanceof \Carbon\Carbon) ? $targetDate->format('F d, Y') : $targetDate;

        $grouped = [];
        foreach ($intentions as $type => $group) {
            $category = 'SPECIAL INTENTIONS';
            $typeLower = strtolower($type);

            if (str_contains($typeLower, 'thanksgiving')) {
                $category = 'THANKSGIVING';
            } elseif (str_contains($typeLower, 'repose')) {
                $category = 'ETERNAL REPOSE';
            }

            if (!isset($grouped[$category])) {
                $grouped[$category] = collect();
            }
            $grouped[$category] = $grouped[$category]->concat($group);
        }

        $slides = [];
        foreach ($grouped as $category => $items) {
            $isRepose = ($category === 'ETERNAL REPOSE');
            $isThanks = ($category === 'THANKSGIVING');

            // 1. Intro Slide
            $slides[] = [
                'type' => 'intro',
                'category' => $category,
                'offsetX' => 75,
                'offsetY' => 100,
                'isRepose' => $isRepose,
                'mainText' => $isRepose ? 'LASTLY, LET US PRAY FOR THE' : 'LET US JOIN OUR FELLOW PARISHIONERS IN THEIR',
                'boldText' => $category,
                'footerText' => $isRepose ? 'OF OUR DEPARTED LOVED ONES.' : ($isThanks ? 'FOR THE SPECIAL BLESSINGS AND GRACES RECEIVED FROM THE LORD.' : 'IN COMMEMORATING THEIR BIRTHDAYS, WEDDING ANNIVERSARIES AND PRAY FOR THEIR SPEEDY RECOVERY.')
            ];

            // 2. List Slides (Chunked)
            $chunks = $items->chunk(12);
            foreach ($chunks as $chunk) {
                $slides[] = [
                    'type' => 'list',
                    'category' => $category . ($isThanks ? ' FOR THE BLESSINGS' : ''),
                    'offsetX' => 50,
                    'offsetY' => 90,
                    'isRepose' => $isRepose,
                    'items' => $chunk->map(function ($i) {
                        return [
                            'id' => $i->id,
                            'name' => strtoupper($i->full_name),
                            'description' => $i->raw_message
                        ];
                    })->values()->toArray()
                ];
            }
        }

        return response()->json([
            'date' => $displayDate,
            'slides' => $slides
        ]);
    }

    public function createIntention()
    {
        return view('admin.intentions-create');
    }

    public function storeIntention(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'intentionType' => 'required|string',
            'preferredDate' => 'nullable|date',
            'massTime' => 'nullable|string',
            'description' => 'nullable|string',
            'paymentMethod' => 'nullable|string',
        ]);

        MassIntention::create([
            'full_name' => $validated['fullName'],
            'intention_type' => $validated['intentionType'],
            'raw_message' => $validated['description'] ?? null,
            'preferred_date' => $validated['preferredDate'],
            'mass_time' => $validated['massTime'],
            'status' => 'approved', // Admin submitted are approved by default
            'payment_method' => $validated['paymentMethod'],
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.intentions')->with('success', 'Mass intention created successfully.');
    }

    public function createGoogleSlides(Request $request)
    {
        $slidesData = $request->input('slides');
        $oauthConfigPath = storage_path('app/google_oauth_client.json');
        $setting = Setting::where('key', 'google_token')->first();

        if (!file_exists($oauthConfigPath) || !$setting) {
            return response()->json([
                'error' => 'Google Auth Missing',
                'message' => 'Please visit /google/auth to connect your account.'
            ], 403);
        }

        try {
            $client = new \Google\Client();
            $client->setAuthConfig($oauthConfigPath);
            $client->addScope('https://www.googleapis.com/auth/presentations');
            $client->addScope('https://www.googleapis.com/auth/drive');

            // Load saved token from database
            $accessToken = json_decode($setting->value, true);
            $client->setAccessToken($accessToken);

            // Auto-refresh if expired
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    $setting->update(['value' => json_encode($client->getAccessToken())]);
                } else {
                    return response()->json(['error' => 'Token Expired', 'message' => 'Please visit /google/auth to re-connect.'], 403);
                }
            }

            $slidesService = new \Google\Service\Slides($client);
            $driveService = new \Google\Service\Drive($client);

            $targetDate = $request->input('date') ?? date('Y-m-d');
            $presentationTitle = 'Mass Intentions - ' . $targetDate;
            $targetFolderId = '1FLhWonUy8eaE-VxEH_-Tp1hQ79REcbDV'; // MASS INTENTION SLIDES folder
            
            // 1. Search for existing presentation for this date in the folder
            $presentationId = null;
            $initialSlideId = null;
            $isUpdate = false;
            
            try {
                $query = "name = '" . str_replace("'", "\\'", $presentationTitle) . "' and '" . $targetFolderId . "' in parents and trashed = false";
                $files = $driveService->files->listFiles(['q' => $query, 'fields' => 'files(id, name)']);
                
                if (count($files->getFiles()) > 0) {
                    $presentationId = $files->getFiles()[0]->getId();
                    $presentation = $slidesService->presentations->get($presentationId);
                    $initialSlideId = $presentation->getSlides()[0]->getObjectId();
                    $existingSlideIds = array_map(fn($s) => $s->getObjectId(), $presentation->getSlides());
                    $isUpdate = true;
                }
            } catch (\Exception $e) {
                \Log::warning('Search for existing presentation failed: ' . $e->getMessage());
            }

            if (!$presentationId) {
                // 1.1 Create new presentation
                $presentation = $slidesService->presentations->create(
                    new \Google\Service\Slides\Presentation(['title' => $presentationTitle])
                );
                $presentationId = $presentation->getPresentationId();
                $initialSlideId = $presentation->getSlides()[0]->getObjectId();

                // 1.2 Move presentation to the target folder
                try {
                    $file = $driveService->files->get($presentationId, ['fields' => 'parents']);
                    $previousParents = implode(',', $file->getParents());
                    $driveService->files->update($presentationId, new \Google\Service\Drive\DriveFile(), [
                        'addParents'    => $targetFolderId,
                        'removeParents' => $previousParents,
                        'fields'        => 'id, parents'
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Could not move presentation to folder: ' . $e->getMessage());
                }
            }

            // 2. Build all slides
            $requests = [];
            $runId = uniqid(); // Unique ID for this specific batch execution
            
            // If updating, we'll keep track of old slides to delete them later
            $oldSlideIds = $isUpdate ? $existingSlideIds : [];

            foreach ($slidesData as $index => $s) {
                $pageId = "slide_{$runId}_{$index}";
                
                $requests[] = new \Google\Service\Slides\Request([
                    'createSlide' => [
                        'objectId' => $pageId,
                        'slideLayoutReference' => ['predefinedLayout' => 'BLANK']
                    ]
                ]);

                if ($s['type'] === 'intro') {
                    $shapeId = "shape_intro_{$runId}_{$index}";
                    $requests[] = new \Google\Service\Slides\Request([
                        'createShape' => [
                            'objectId' => $shapeId,
                            'shapeType' => 'TEXT_BOX',
                            'elementProperties' => [
                                'pageObjectId' => $pageId,
                                'size' => [
                                    'height' => ['magnitude' => 3000000, 'unit' => 'EMU'],
                                    'width' => ['magnitude' => 8000000, 'unit' => 'EMU']
                                ],
                                'transform' => [
                                    'scaleX' => 1,
                                    'scaleY' => 1,
                                    'translateX' => ($s['offsetX'] ?? 75) * 12700,
                                    'translateY' => ($s['offsetY'] ?? 100) * 12700,
                                    'unit' => 'EMU'
                                ]
                            ]
                        ]
                    ]);
                    $mainText   = ($s['mainText'] ?? '') . "\n";
                    $boldText   = ($s['boldText'] ?? '') . "\n";
                    $footerText = ($s['footerText'] ?? '');
                    $fullText   = $mainText . $boldText . $footerText;

                    $requests[] = new \Google\Service\Slides\Request([
                        'insertText' => ['objectId' => $shapeId, 'text' => $fullText]
                    ]);

                    // Style the Bold Category Text (Red & Bold)
                    $requests[] = new \Google\Service\Slides\Request([
                        'updateTextStyle' => [
                            'objectId' => $shapeId,
                            'style' => [
                                'bold' => true,
                                'foregroundColor' => [
                                    'opaqueColor' => [
                                        'rgbColor' => ['red' => 1.0, 'green' => 0.0, 'blue' => 0.0]
                                    ]
                                ],
                                'fontSize' => ['magnitude' => 45, 'unit' => 'PT']
                            ],
                            'textRange' => [
                                'type' => 'FIXED_RANGE',
                                'startIndex' => mb_strlen($mainText, 'UTF-8'),
                                'endIndex' => mb_strlen($mainText, 'UTF-8') + mb_strlen($boldText, 'UTF-8')
                            ],
                            'fields' => 'bold,foregroundColor,fontSize'
                        ]
                    ]);

                    // Center all text in intro shape
                    $requests[] = new \Google\Service\Slides\Request([
                        'updateParagraphStyle' => [
                            'objectId' => $shapeId,
                            'style' => ['alignment' => 'CENTER'],
                            'fields' => 'alignment'
                        ]
                    ]);
                } else {
                    $barId = "bar_list_{$runId}_{$index}";
                    $headerId = "header_list_{$runId}_{$index}";
                    $shapeId = "shape_list_{$runId}_{$index}";

                    // 1. Create Black Header Bar
                    $requests[] = new \Google\Service\Slides\Request([
                        'createShape' => [
                            'objectId' => $barId,
                            'shapeType' => 'RECTANGLE',
                            'elementProperties' => [
                                'pageObjectId' => $pageId,
                                'size' => [
                                    'height' => ['magnitude' => 500000, 'unit' => 'EMU'],
                                    'width' => ['magnitude' => 9144000, 'unit' => 'EMU']
                                ],
                                'transform' => ['scaleX' => 1, 'scaleY' => 1, 'translateX' => 0, 'translateY' => 0, 'unit' => 'EMU']
                            ]
                        ]
                    ]);
                    $requests[] = new \Google\Service\Slides\Request([
                        'updateShapeProperties' => [
                            'objectId' => $barId,
                            'shapeProperties' => [
                                'shapeBackgroundFill' => ['solidFill' => ['color' => ['rgbColor' => ['red' => 0.0, 'green' => 0.0, 'blue' => 0.0]]]]
                            ],
                            'fields' => 'shapeBackgroundFill'
                        ]
                    ]);

                    // 2. Create Header Text (White)
                    $requests[] = new \Google\Service\Slides\Request([
                        'createShape' => [
                            'objectId' => $headerId,
                            'shapeType' => 'TEXT_BOX',
                            'elementProperties' => [
                                'pageObjectId' => $pageId,
                                'size' => [
                                    'height' => ['magnitude' => 450000, 'unit' => 'EMU'],
                                    'width' => ['magnitude' => 9144000, 'unit' => 'EMU']
                                ],
                                'transform' => ['scaleX' => 1, 'scaleY' => 1, 'translateX' => 0, 'translateY' => 25000, 'unit' => 'EMU']
                            ]
                        ]
                    ]);
                    $categoryText = ($s['category'] ?? 'INTENTIONS');
                    $requests[] = new \Google\Service\Slides\Request([
                        'insertText' => ['objectId' => $headerId, 'text' => $categoryText]
                    ]);
                    $requests[] = new \Google\Service\Slides\Request([
                        'updateTextStyle' => [
                            'objectId' => $headerId,
                            'style' => [
                                'bold' => true,
                                'foregroundColor' => ['opaqueColor' => ['rgbColor' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]]],
                                'fontSize' => ['magnitude' => 18, 'unit' => 'PT']
                            ],
                            'fields' => 'bold,foregroundColor,fontSize'
                        ]
                    ]);
                    $requests[] = new \Google\Service\Slides\Request([
                        'updateParagraphStyle' => [
                            'objectId' => $headerId,
                            'style' => ['alignment' => 'CENTER'],
                            'fields' => 'alignment'
                        ]
                    ]);

                    // 3. Create List Box
                    $requests[] = new \Google\Service\Slides\Request([
                        'createShape' => [
                            'objectId' => $shapeId,
                            'shapeType' => 'TEXT_BOX',
                            'elementProperties' => [
                                'pageObjectId' => $pageId,
                                'size' => [
                                    'height' => ['magnitude' => 4000000, 'unit' => 'EMU'],
                                    'width' => ['magnitude' => 8000000, 'unit' => 'EMU']
                                ],
                                'transform' => [
                                    'scaleX' => 1, 'scaleY' => 1,
                                    'translateX' => ($s['offsetX'] ?? 50) * 12700,
                                    'translateY' => (($s['offsetY'] ?? 90) + 10) * 12700,
                                    'unit' => 'EMU'
                                ]
                            ]
                        ]
                    ]);

                    $fullListText = "";
                    $styleRanges = [];
                    $currentIndex = 0;

                    foreach (($s['items'] ?? []) as $item) {
                        $bullet = ($s['isRepose'] ? "+ " : "• ");
                        $name = ($item['name'] ?? 'NAME') . "\n";
                        
                        $nameStart = $currentIndex + mb_strlen($bullet, 'UTF-8');
                        $nameEnd = $nameStart + mb_strlen($name, 'UTF-8');
                        
                        $fullListText .= $bullet . $name;
                        $styleRanges[] = ['start' => $nameStart, 'end' => $nameEnd];
                        $currentIndex = mb_strlen($fullListText, 'UTF-8');

                        if (!empty($item['description'])) {
                            $desc = "  (" . $item['description'] . ")\n";
                            $fullListText .= $desc;
                            $currentIndex = mb_strlen($fullListText, 'UTF-8');
                        }
                    }

                    $requests[] = new \Google\Service\Slides\Request([
                        'insertText' => ['objectId' => $shapeId, 'text' => $fullListText]
                    ]);

                    // Style the names as Blue & Bold
                    foreach ($styleRanges as $range) {
                        $requests[] = new \Google\Service\Slides\Request([
                            'updateTextStyle' => [
                                'objectId' => $shapeId,
                                'style' => [
                                    'bold' => true,
                                    'foregroundColor' => [
                                        'opaqueColor' => [
                                            'rgbColor' => ['red' => 0.0, 'green' => 0.0, 'blue' => 0.7] // Dark Blue
                                        ]
                                    ]
                                ],
                                'textRange' => [
                                    'type' => 'FIXED_RANGE',
                                    'startIndex' => $range['start'],
                                    'endIndex' => $range['end']
                                ],
                                'fields' => 'bold,foregroundColor'
                            ]
                        ]);
                    }
                }
            }

            // 2.2 If updating, delete the original old slides *after* creating new ones
            if ($isUpdate && !empty($oldSlideIds)) {
                foreach ($oldSlideIds as $oldId) {
                    $requests[] = new \Google\Service\Slides\Request([
                        'deleteObject' => ['objectId' => $oldId]
                    ]);
                }
            } else if (!$isUpdate && isset($initialSlideId)) {
                // If new, delete the default first slide
                $requests[] = new \Google\Service\Slides\Request([
                    'deleteObject' => ['objectId' => $initialSlideId]
                ]);
            }

            // 3. Execute batchUpdate FIRST before sharing
            if (!empty($requests)) {
                $batchUpdateRequest = new \Google\Service\Slides\BatchUpdatePresentationRequest(['requests' => $requests]);
                $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
            }

            // 4. Share with Gmail AFTER slides are built (ignore if this fails)
            try {
                $permission = new \Google\Service\Drive\Permission([
                    'type' => 'user',
                    'role' => 'writer',
                    'emailAddress' => 'publicojamesmangao25@gmail.com',
                ]);
                $driveService->permissions->create($presentationId, $permission, [
                    'sendNotificationEmail' => false,
                ]);
            } catch (\Exception $e) {
                // Sharing failed but slides were built — still return the URL
            }

            return response()->json([
                'success' => true,
                'url' => 'https://docs.google.com/presentation/d/' . $presentationId . '/edit'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'API Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

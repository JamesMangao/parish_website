<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class GoogleSlidesController extends Controller
{
    public function create(Request $request)
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
            $client->setHttpClient(new \GuzzleHttp\Client());
            $client->setAuthConfig($oauthConfigPath);
            $client->addScope('https://www.googleapis.com/auth/presentations');
            $client->addScope('https://www.googleapis.com/auth/drive');

            $accessToken = json_decode($setting->value, true);
            $client->setAccessToken($accessToken);

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
            $targetFolderId = config('services.google.folder_id', '1FLhWonUy8eaE-VxEH_-Tp1hQ79REcbDV');

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
                $presentation = $slidesService->presentations->create(
                    new \Google\Service\Slides\Presentation(['title' => $presentationTitle])
                );
                $presentationId = $presentation->getPresentationId();
                $initialSlideId = $presentation->getSlides()[0]->getObjectId();

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

            $requests = [];
            $runId = uniqid();
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

                    foreach ($styleRanges as $range) {
                        $requests[] = new \Google\Service\Slides\Request([
                            'updateTextStyle' => [
                                'objectId' => $shapeId,
                                'style' => [
                                    'bold' => true,
                                    'foregroundColor' => [
                                        'opaqueColor' => [
                                            'rgbColor' => ['red' => 0.0, 'green' => 0.0, 'blue' => 0.7]
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

            if ($isUpdate && !empty($oldSlideIds)) {
                foreach ($oldSlideIds as $oldId) {
                    $requests[] = new \Google\Service\Slides\Request([
                        'deleteObject' => ['objectId' => $oldId]
                    ]);
                }
            } else if (!$isUpdate && isset($initialSlideId)) {
                $requests[] = new \Google\Service\Slides\Request([
                    'deleteObject' => ['objectId' => $initialSlideId]
                ]);
            }

            if (!empty($requests)) {
                $batchUpdateRequest = new \Google\Service\Slides\BatchUpdatePresentationRequest(['requests' => $requests]);
                $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
            }

            try {
                $permission = new \Google\Service\Drive\Permission([
                    'type' => 'user',
                    'role' => 'writer',
                    'emailAddress' => config('services.google.share_email', env('PARISH_OFFICE_EMAIL', 'publicojamesmangao25@gmail.com')),
                ]);
                $driveService->permissions->create($presentationId, $permission, [
                    'sendNotificationEmail' => false,
                ]);
            } catch (\Exception $e) {
                // Sharing failed but slides were built
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

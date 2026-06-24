<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use Illuminate\Http\Request;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Alignment;

class PptController extends Controller
{
    public function generate(Request $request)
    {
        $slidesData = $request->input('slides');

        if (!$slidesData) {
            $preview = $this->previewPPT();
            $slidesData = json_decode($preview->getContent(), true)['slides'];
        }

        $objPHPPresentation = new PhpPresentation();
        $oLayout = new \PhpOffice\PhpPresentation\DocumentLayout();
        $oLayout->setDocumentLayout(\PhpOffice\PhpPresentation\DocumentLayout::LAYOUT_SCREEN_16X9);
        $objPHPPresentation->getLayout()->setDocumentLayout($oLayout);

        foreach ($slidesData as $s) {
            $slide = $objPHPPresentation->createSlide();
            $bg = new \PhpOffice\PhpPresentation\Slide\Background\Color();
            $bg->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));
            $slide->setBackground($bg);

            if ($s['type'] === 'intro') {
                $slide->createLineShape(20, 20, 930, 20)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(20, 520, 930, 520)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(20, 20, 20, 520)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(930, 20, 930, 520)->getBorder()->getColor()->setARGB('FF000000');

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
                $headerBar = $slide->createRichTextShape();
                $headerBar->setHeight(50)->setWidth(950)->setOffsetX(0)->setOffsetY(0);
                $headerBar->setFill((new \PhpOffice\PhpPresentation\Style\Fill())->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000')));
                $headerBar->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                $titleText = $s['category'] ?? 'INTENTIONS';
                $title = $headerBar->createTextRun($titleText);
                $title->getFont()->setBold(true)->setSize(24)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));

                $bodyShape = $slide->createRichTextShape();
                $bodyShape->setHeight(420)->setWidth(850)
                    ->setOffsetX($s['offsetX'] ?? 50)
                    ->setOffsetY(($s['offsetY'] ?? 90) + 10);

                foreach ($s['items'] as $item) {
                    $para = $bodyShape->createParagraph();
                    $bullet = $s['isRepose'] ? '+ ' : '• ';

                    $nameRun = $para->createTextRun($bullet . strtoupper($item['name']));
                    $nameRun->getFont()->setBold(true)->setSize(22)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000080'));

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

    public function preview()
    {
        $today = now()->startOfDay();
        $targetDate = $today;

        $existsToday = MassIntention::where('status', 'approved')
            ->whereDate('preferred_date', $today)
            ->exists();

        if (!$existsToday) {
            $earliestFuture = MassIntention::where('status', 'approved')
                ->where('preferred_date', '>', $today)
                ->min('preferred_date');

            if ($earliestFuture) {
                $targetDate = is_string($earliestFuture) ? \Carbon\Carbon::parse($earliestFuture) : $earliestFuture;
            }
        }

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
                            'name' => strtoupper(!empty($i->raw_message) ? $i->raw_message : $i->full_name),
                            'description' => null
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
}

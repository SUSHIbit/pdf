<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Text;
use Illuminate\Http\UploadedFile;
use ZipArchive;

class FileProcessor
{
    public function extractText(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        return match($extension) {
            'pdf' => $this->extractFromPdf($file),
            'docx', 'doc' => $this->extractFromWord($file),
            'pptx' => $this->extractFromPowerPoint($file),
            'txt' => file_get_contents($file->getRealPath()),
            default => throw new \InvalidArgumentException('Unsupported file type')
        };
    }

    private function extractFromPdf(UploadedFile $file): string
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($file->getRealPath());
            return $pdf->getText();
        } catch (\Exception $e) {
            throw new \Exception('Could not extract text from PDF: ' . $e->getMessage());
        }
    }

    private function extractFromWord(UploadedFile $file): string
    {
        try {
            $phpWord = IOFactory::load($file->getRealPath());
            $text = '';
            
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $text .= $this->extractTextFromElement($element);
                }
            }
            
            return trim($text);
        } catch (\Exception $e) {
            throw new \Exception('Could not extract text from Word document: ' . $e->getMessage());
        }
    }

    private function extractFromPowerPoint(UploadedFile $file): string
    {
        try {
            $zip = new ZipArchive;
            $text = '';
            
            if ($zip->open($file->getRealPath()) === TRUE) {
                $slideNumber = 1;
                
                // Loop through slides
                for ($i = 1; $i <= 50; $i++) {
                    $slideXml = $zip->getFromName("ppt/slides/slide{$i}.xml");
                    if ($slideXml === false) {
                        break; // No more slides
                    }
                    
                    $text .= "=== Slide {$slideNumber} ===\n\n";
                    
                    // Parse XML and extract text
                    $xml = simplexml_load_string($slideXml);
                    if ($xml !== false) {
                        $xml->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
                        $xml->registerXPathNamespace('p', 'http://schemas.openxmlformats.org/presentationml/2006/main');
                        
                        // Extract all text nodes
                        $textElements = $xml->xpath('//a:t');
                        foreach ($textElements as $textElement) {
                            $text .= (string)$textElement . "\n";
                        }
                    }
                    
                    $text .= "\n";
                    $slideNumber++;
                }
                
                $zip->close();
                
                if (empty(trim($text))) {
                    // Fallback method using basic string extraction
                    $content = file_get_contents($file->getRealPath());
                    $text = $this->extractTextFromPptxContent($content);
                }
            } else {
                throw new \Exception('Could not open PowerPoint file');
            }
            
            return trim($text);
        } catch (\Exception $e) {
            throw new \Exception('Could not extract text from PowerPoint: ' . $e->getMessage());
        }
    }

    private function extractTextFromPptxContent(string $content): string
    {
        // Simple fallback method to extract readable text from PPTX binary content
        $text = '';
        
        // Remove null bytes and non-printable characters
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', ' ', $content);
        
        // Extract words that are likely to be slide content
        preg_match_all('/[A-Za-z][A-Za-z0-9\s\-.,!?]{2,50}[A-Za-z0-9.,!?]/', $cleaned, $matches);
        
        if (!empty($matches[0])) {
            $words = array_unique($matches[0]);
            $text = implode("\n", $words);
        }
        
        return $text;
    }

    private function extractTextFromElement($element): string
    {
        $text = '';
        
        // Handle different types of Word document elements
        if ($element instanceof Text) {
            $text .= $element->getText() . ' ';
        } elseif ($element instanceof TextRun) {
            // Fixed: Properly handle TextRun elements
            foreach ($element->getElements() as $textElement) {
                if ($textElement instanceof Text) {
                    $text .= $textElement->getText() . ' ';
                }
            }
        } elseif (method_exists($element, 'getText')) {
            // Only call getText() if it exists and handle the result properly
            $elementText = $element->getText();
            if (is_string($elementText)) {
                $text .= $elementText . ' ';
            }
        } elseif (method_exists($element, 'getElements')) {
            // Recursively handle nested elements
            foreach ($element->getElements() as $subElement) {
                $text .= $this->extractTextFromElement($subElement);
            }
        }
        
        return $text . "\n";
    }
}
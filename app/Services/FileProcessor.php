<?php
// app/Services/FileProcessor.php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Text;
use Illuminate\Http\UploadedFile;

class FileProcessor
{
    public function extractText(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        return match($extension) {
            'pdf' => $this->extractFromPdf($file),
            'docx', 'doc' => $this->extractFromWord($file),
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

    private function extractTextFromElement($element): string
    {
        $text = '';
        
        // Handle different types of Word document elements
        if ($element instanceof Text) {
            $text .= $element->getText() . ' ';
        } elseif ($element instanceof TextRun) {
            foreach ($element->getElements() as $textElement) {
                if ($textElement instanceof Text) {
                    $text .= $textElement->getText() . ' ';
                }
            }
        } elseif (method_exists($element, 'getText')) {
            $text .= $element->getText() . ' ';
        } elseif (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $subElement) {
                $text .= $this->extractTextFromElement($subElement);
            }
        }
        
        return $text . "\n";
    }
}
<?php

namespace App\Sidebar\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Processa upload de imagem única e slides para o widget image_link.
 * Extraído do componente Livewire para manter o componente enxuto.
 */
class ImageLinkProcessor
{
    public function process(array $formData): array
    {
        $data = [
            'image_width'  => $formData['image_width'] ?? 300,
            'image_height' => $formData['image_height'] ?? 150,
        ];

        if ($formData['display_mode'] === 'single') {
            return $this->processSingle($data, $formData);
        }

        return $this->processSlide($data, $formData);
    }

    // ─── Single ───────────────────────────────────────────────────────────────

    private function processSingle(array $data, array $form): array
    {
        // Limpar dados de slide
        $data['slide_images']    = null;
        $data['slide_interval']  = null;
        $data['slide_autoplay']  = false;
        $data['slide_controls']  = false;
        $data['slide_indicators']= false;

        $imageFile     = $form['imageFile'] ?? null;
        $existingImage = $form['existingImage'] ?? '';

        if ($imageFile && is_object($imageFile) && method_exists($imageFile, 'store')) {
            $this->deleteIfExists($existingImage);
            $data['image'] = $imageFile->store('sidebar-images', 'public');
        } else {
            $data['image'] = $existingImage ?: null;
        }

        $data['link'] = $form['link'] ?: null;

        return $data;
    }

    // ─── Slide ────────────────────────────────────────────────────────────────

    private function processSlide(array $data, array $form): array
    {
        $data['slide_interval']   = $form['slide_interval'] ?? 5000;
        $data['slide_autoplay']   = $form['slide_autoplay'] ?? true;
        $data['slide_controls']   = $form['slide_controls'] ?? true;
        $data['slide_indicators'] = $form['slide_indicators'] ?? true;

        // Remover imagem única se existir
        $this->deleteIfExists($form['existingImage'] ?? '');
        $data['image'] = null;
        $data['link']  = null;

        $slides = [];

        foreach ($form['slide_items'] as $item) {
            $file = $item['file'] ?? null;

            if ($file && is_object($file) && method_exists($file, 'store')) {
                $this->deleteIfExists($item['existing'] ?? '');
                $slides[] = [
                    'image' => $file->store('sidebar-slides', 'public'),
                    'link'  => $item['link'],
                ];
            } elseif (!empty($item['existing'])) {
                $slides[] = [
                    'image' => $item['existing'],
                    'link'  => $item['link'],
                ];
            }
        }

        $data['slide_images'] = count($slides) >= 2 ? $slides : null;

        return $data;
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
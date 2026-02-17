<?php

namespace App\Traits\SweetAlert2;

trait FlashToast
{
    public function flashToastSuccess(string $text = '', string $title = 'Éxito'): void
    {
        $this->flashToast($text, $title, 'success');
    }

    public function flashToastError(string $text = '', string $title = 'Error'): void
    {
        $this->flashToast($text, $title, 'error');
    }

    public function flashToastWarning(string $text = '', string $title = 'Advertencia'): void
    {
        $this->flashToast($text, $title, 'warning');
    }

    public function flashToastInfo(string $text = '', string $title = 'Información'): void
    {
        $this->flashToast($text, $title, 'info');
    }

    public function flashToastQuestion(string $text = '', string $title = '¿Estás seguro?'): void
    {
        $this->flashToast($text, $title, 'question');
    }

    protected function flashToast(string $text, string $title, string $icon): void
    {
        session()->flash('sweetalert2_flash', [
            'title' => $title,
            'text'  => $text,
            'icon'  => $icon,
            'toast' => true,
            'position' => 'top-end',
            'timer' => 4000,
            'showConfirmButton' => false,
            'customClass' => [
                'popup' => 'custom-toast-position'
            ]
        ]);
    }
}

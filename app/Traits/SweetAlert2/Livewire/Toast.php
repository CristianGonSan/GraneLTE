<?php

namespace App\Traits\SweetAlert2\Livewire;

trait Toast
{
    public function toastSuccess(string $text = '', string $title = 'Éxito'): void
    {
        $this->dispatchToast($text, $title, 'success');
    }

    public function toastError(string $text = '', string $title = 'Error'): void
    {
        $this->dispatchToast($text, $title, 'error');
    }

    public function toastWarning(string $text = '', string $title = 'Advertencia'): void
    {
        $this->dispatchToast($text, $title, 'warning');
    }

    public function toastInfo(string $text = '', string $title = 'Información'): void
    {
        $this->dispatchToast($text, $title, 'info');
    }

    public function toastQuestion(string $text = '', string $title = '¿Estás seguro?'): void
    {
        $this->dispatchToast($text, $title, 'question');
    }

    protected function dispatchToast(string $text, string $title, string $icon): void
    {
        $config = [
            'title' => $title,
            'text'  => $text,
            'icon'  => $icon,
            'toast' => true,
            'position' => 'top-end',
            'timer' => 3000,
            'showConfirmButton' => false,
            'customClass' => [
                'popup' => 'custom-toast-position'
            ]
        ];

        $this->js('Swal.fire(' . json_encode($config) . ');');
    }
}

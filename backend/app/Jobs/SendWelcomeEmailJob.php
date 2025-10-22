<?php

namespace App\Jobs;

use App\Models\User; // <-- Adicione
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // <-- Adicione

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // <-- Adicione

    /**
     * Create a new job instance.
     */
    public function __construct(User $user) // <-- Modifique
    {
        $this->user = $user; // <-- Adicione
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simula o envio do e-mail escrevendo no log
        Log::info('E-mail de boas-vindas simulado para: ' . $this->user->email);
    }
}
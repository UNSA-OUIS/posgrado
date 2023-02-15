<?php

namespace App\Jobs;

use App\Mail\CobroRealizadoMailable;
use App\Mail\DenunciaRegistradaMailable;
use App\Mail\DenunciaRespondidaMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarCorreosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->data['tipo'] === 'registrado'){
            
            $email = new DenunciaRegistradaMailable($this->data);
        }
        else if ($this->data['tipo'] === 'respondida') {
            $email = new DenunciaRespondidaMailable($this->data);
        }
        Mail::to($this->data['user']->email)->send($email);
    }
}

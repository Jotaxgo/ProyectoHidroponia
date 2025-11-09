<?php

namespace App\Events;

use App\Models\LecturaSensor;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LecturaSensorRegistrada
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public LecturaSensor $lectura)
    {
        //
    }
}

<?php

namespace App\Rules;

use App\Models\Barbero;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueEmailAcrossTables implements ValidationRule
{
    protected $ignoreBarberoId;
    protected $ignoreUserId;

    public function __construct(?int $ignoreBarberoId = null, ?int $ignoreUserId = null)
    {
        $this->ignoreBarberoId = $ignoreBarberoId;
        $this->ignoreUserId = $ignoreUserId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verificar en tabla barberos
        $barberoQuery = Barbero::where('email', $value);
        if ($this->ignoreBarberoId) {
            $barberoQuery->where('id', '!=', $this->ignoreBarberoId);
        }

        if ($barberoQuery->exists()) {
            $fail('Este correo electrónico ya está registrado por otro barbero.');
            return;
        }

        // Verificar en tabla users
        $userQuery = User::where('email', $value);
        if ($this->ignoreUserId) {
            $userQuery->where('id', '!=', $this->ignoreUserId);
        }

        if ($userQuery->exists()) {
            $fail('Este correo electrónico ya está registrado por otro usuario en el sistema.');
            return;
        }
    }
}
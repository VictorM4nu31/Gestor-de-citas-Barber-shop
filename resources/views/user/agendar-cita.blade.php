<!-- resources/views/appointment.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <h1 class="text-2xl font-bold mb-6">Agendar Cita</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Calendario de Disponibilidad -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Calendario de Disponibilidad</h2>
                <div class="border rounded-lg p-4">
                    <!-- Espacio para el calendario -->
                    <div class="flex justify-between items-center mb-4">
                        <button class="focus:outline-none">
                            &lt;
                        </button>
                        <span>April 2024</span>
                        <button class="focus:outline-none">
                            &gt;
                        </button>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center">
                        <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
                        <!-- Días del mes -->
                        <div class="text-gray-400">31</div><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div>
                        <div>7</div><div>8</div><div>9</div><div>10</div><div>11</div><div>12</div><div>13</div>
                        <div>14</div><div>15</div><div>16</div><div>17</div><div>18</div><div>19</div><div>20</div>
                        <div>21</div><div>22</div><div>23</div><div>24</div><div>25</div><div>26</div><div>27</div>
                        <div>28</div><div>29</div><div>30</div><div class="text-gray-400">1</div><div class="text-gray-400">2</div><div class="text-gray-400">3</div><div class="text-gray-400">4</div>
                    </div>
                </div>
            </div>
            <!-- Seleccionar Barbero y Servicio -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Seleccionar Barbero y Servicio</h2>
                <div class="border rounded-lg p-4">
                    <div class="mb-4">
                        <label for="barbero" class="block text-gray-700">Barbero</label>
                        <select id="barbero" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                            <option>Seleccionar barbero</option>
                            <!-- Opciones de barbero -->
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="servicio" class="block text-gray-700">Servicio</label>
                        <select id="servicio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                            <option>Seleccionar servicio</option>
                            <!-- Opciones de servicio -->
                        </select>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700">Costo estimado: <span class="font-semibold">$20</span></p>
                    </div>
                    <button class="w-full bg-black text-white py-2 rounded-md">Agendar Cita</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

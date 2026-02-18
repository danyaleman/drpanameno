<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\VaccineRecord;
use App\Notification;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;

class VaccineReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaccines:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for upcoming vaccines (today and tomorrow) and notifies admins and doctors.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for upcoming vaccines...');

        // 1. Obtener usuarios a notificar (Admins y Doctores)
        $adminRole = Sentinel::findRoleBySlug('admin');
        $admins = $adminRole ? $adminRole->users : collect();

        $doctorRole = Sentinel::findRoleBySlug('doctor');
        $doctors = $doctorRole ? $doctorRole->users : collect();

        // Unir ambos grupos y eliminar duplicados por ID
        $recipients = $admins->merge($doctors)->unique('id');
        
        $this->info("Found {$recipients->count()} recipients (Admins + Doctors).");

        // 2. Buscar vacunas pendientes para hoy y mañana
        // Usamos whereDate para ignorar la hora
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        $records = VaccineRecord::with(['patient', 'vaccine'])
            ->where('status', 'pending')
            ->where(function($q) use ($today, $tomorrow) {
                $q->whereDate('scheduled_date', $today)
                  ->orWhereDate('scheduled_date', $tomorrow);
            })
            ->get();

        $this->info("Found {$records->count()} pending vaccines for today/tomorrow.");

        if ($records->isEmpty()) {
            return 0;
        }

        $count = 0;
        foreach ($records as $record) {
            if (!$record->patient || !$record->vaccine) continue;

            $dateLabel = ($record->scheduled_date == $today) ? 'HOY' : 'MAÑANA';
            $title = "💉 Vacuna {$dateLabel}: {$record->patient->first_name} {$record->patient->last_name} — {$record->vaccine->name} ({$record->dose_label})";

            // Enviar a cada destinatario
            foreach ($recipients as $user) {
                // Verificar si ya existe una notificación idéntica HOY para este usuario y record
                // Para evitar spam si el comando corre varias veces al día
                $exists = Notification::where('to_user', $user->id)
                    ->where('notification_type_id', 5)
                    ->where('data', $record->id)
                    ->whereDate('created_at', Carbon::today())
                    ->exists();

                if (!$exists) {
                    Notification::create([
                        'notification_type_id' => 5, // 5 = Vaccine
                        'title'      => $title,
                        'data'       => $record->id, // Vaccine Record ID
                        'from_user'  => 1, // System/Admin ID (hardcoded 1 for now)
                        'to_user'    => $user->id,
                        'read_at'    => null,
                        'is_deleted' => 0,
                    ]);
                    $count++;
                }
            }
        }

        $this->info("Sent {$count} notifications.");
        return 0;
    }
}

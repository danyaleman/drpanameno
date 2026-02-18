<?php

namespace App\Notifications;

use App\VaccineRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notificación de recordatorio de vacuna próxima.
 *
 * Canales:
 *  - 'database' : Notificación interna (campana del header y dashboard)
 *  - 'mail'     : Correo simulado al paciente
 */
class VaccineReminderNotification extends Notification
{
    use Queueable;

    public VaccineRecord $record;

    public function __construct(VaccineRecord $record)
    {
        $this->record = $record;
    }

    /**
     * Canales de entrega.
     * Usamos 'database' para la campana interna.
     * Usamos 'mail' para simular el correo al paciente.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /* =====================
     |  DATABASE (campana interna)
     ===================== */

    /**
     * Datos que se guardan en la tabla 'notifications' de Laravel.
     * Estos datos aparecen en la campana del header y en el dashboard.
     */
    public function toDatabase($notifiable): array
    {
        $patient = $this->record->patient;
        $vaccine = $this->record->vaccine;

        return [
            'type'             => 'vaccine_reminder',
            'vaccine_record_id'=> $this->record->id,
            'patient_id'       => $patient->id,
            'patient_name'     => $patient->first_name . ' ' . $patient->last_name,
            'patient_phone'    => $patient->phone_primary ?? $patient->phone_secondary ?? 'N/A',
            'vaccine_name'     => $vaccine->name,
            'dose_label'       => $this->record->dose_label,
            'dose_number'      => $this->record->dose_number,
            'scheduled_date'   => $this->record->scheduled_date->format('d/m/Y'),
            'message'          => "Recordatorio: {$patient->first_name} {$patient->last_name} tiene pendiente la {$this->record->dose_label} de {$vaccine->name} para el {$this->record->scheduled_date->format('d/m/Y')}.",
            'icon'             => 'bx bx-injection',
        ];
    }

    /* =====================
     |  MAIL (simulado al paciente)
     ===================== */

    /**
     * Correo simulado al paciente.
     * En producción, el $notifiable sería el User del paciente.
     * Aquí lo enviamos al admin/doctor como simulación.
     */
    public function toMail($notifiable): MailMessage
    {
        $patient = $this->record->patient;
        $vaccine = $this->record->vaccine;

        return (new MailMessage)
            ->subject("⚕️ Recordatorio de Vacuna: {$vaccine->name} - {$patient->first_name} {$patient->last_name}")
            ->greeting("Estimado/a {$patient->first_name} {$patient->last_name},")
            ->line("Le recordamos que tiene programada la **{$this->record->dose_label}** de la vacuna **{$vaccine->name}**.")
            ->line("📅 **Fecha programada:** {$this->record->scheduled_date->format('d/m/Y')}")
            ->line("Por favor, comuníquese con nuestra clínica para confirmar su cita.")
            ->action('Ver mi historial de vacunas', url('/dashboard'))
            ->line("Si ya recibió esta vacuna, puede ignorar este mensaje.")
            ->salutation("Atentamente, el equipo médico.");
    }
}

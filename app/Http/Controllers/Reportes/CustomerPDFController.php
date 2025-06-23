<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use TCPDF;

class CustomerPDFController extends Controller
{
    public function exportPDF()
    {
        $customers = Customer::orderBy('id', 'asc')->get();

        // Logo
        $logoPath = public_path('apple-touch-icon.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $imgData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($imgData);
        }

        $gimnasio = 'Gimnasio X';
        $empresa = 'Soluciones en Ingenieria T&J';
        $usuario = auth()->user() ? auth()->user()->name : 'Desconocido';
        $fechaGeneracion = now('America/Lima')->format('d/m/Y H:i');

        // Estadísticas
        $total = $customers->count();
        $activos = $customers->where('status', 1)->count();
        $inactivos = $customers->where('status', 0)->count();

        // ---- Estructura HTML del PDF ----
        $html = '
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td style="width:18%;">
                    '.($logoBase64 ? '<img src="'.$logoBase64.'" height="48">' : '').'
                </td>
                <td style="width:70%; text-align:center;">
                    <div style="font-size:24px; font-weight:bold; color:#222; margin-bottom:3px;">Informe de Clientes</div>
                    <div style="font-size:15px; color:#444; margin-bottom:2px;">'.$gimnasio.'</div>
                </td>
                <td style="width:20%;"></td>
            </tr>
        </table>
        <div style="width:100%; text-align:center; font-size:14px; color:#555; margin-bottom:12px;">
            Reporte detallado de todos los clientes registrados en el sistema.
        </div>
        <table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:11px; table-layout:fixed;">
            <thead>
                <tr style="background-color:#2874A6; color:#fff;">
                    <th style="width:6%; text-align:center;">ID</th>
                    <th style="width:15%; text-align:left;">Nombres</th>
                    <th style="width:15%; text-align:left;">Apellidos</th>
                    <th style="width:11%; text-align:center;">Código</th>
                    <th style="width:16%; text-align:left;">Correo</th>
                    <th style="width:12%; text-align:center;">Teléfono</th>
                    <th style="width:17%; text-align:left;">Dirección</th>
                    <th style="width:8%; text-align:center;">Estado</th>
                </tr>
            </thead>

            <tbody>
        ';

        foreach ($customers as $c) {
        $estadoColor = $c->status == 1 ? "#14A44D" : "#D32F2F";
        $estadoText = $c->status == 1 ? "Activo" : "Inactivo";
        $html .= '
            <tr>
                <td style="width:6%; text-align:center;">'.$c->id.'</td>
                <td style="width:15%;">'.htmlspecialchars($c->first_name).'</td>
                <td style="width:15%;">'.htmlspecialchars($c->last_name).'</td>
                <td style="width:11%; text-align:center;">'.htmlspecialchars($c->code).'</td>
                <td style="width:16%;">'.htmlspecialchars($c->email).'</td>
                <td style="width:12%; text-align:center;">'.htmlspecialchars($c->phone).'</td>
                <td style="width:17%;">'.htmlspecialchars($c->address).'</td>
                <td style="width:8%; text-align:center; color:'.$estadoColor.'; font-weight:bold;">'.$estadoText.'</td>
            </tr>
            ';
        }

        $html .= '
            </tbody>
        </table>
        <br>
        <div style="width:70%; font-size:12px; color:#444; margin-top:4px; margin-bottom:2px;">
            <b>Total de clientes:</b> '.$total.'<br>
            <b>Clientes activos:</b> '.$activos.'<br>
            <b>Clientes inactivos:</b> '.$inactivos.'
        </div>
        <div style="width:100%; font-size:11px; color:#666; text-align:right; margin-top:8px;">
            Usuario: <b>'.$usuario.'</b><br>
            Fecha y hora: <b>'.$fechaGeneracion.'</b>
        </div>
        <hr style="margin:12px 0 2px 0; border:none; border-top:1px solid #BBB;">
        <div style="font-size:10px; color:#888; text-align:center; margin-top:6px;">
            Este informe ha sido generado por encargo de <b>'.$empresa.'</b>, responsable del sistema de membresías y gestión de clientes.<br>
            Todos los derechos reservados &copy; '.date('Y').'
        </div>
        ';

        // ---- PDF ----
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false); // <-- 'L' para landscape
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Laravel');
        $pdf->SetTitle('Informe de Clientes');
        $pdf->SetSubject('Reporte formal de Clientes');
        $pdf->SetMargins(12, 6, 12, true);
        $pdf->SetAutoPageBreak(true, 18);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        if (ob_get_length()) ob_end_clean();

        $pdfOutput = $pdf->Output('informe_clientes.pdf', 'S');
        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="informe_clientes.pdf"');
    }
}

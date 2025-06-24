<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use TCPDF;

class PaymentMethodPDFController extends Controller
{
    public function exportPDF()
    {
        $methods = PaymentMethod::orderBy('id')->get();

        // Logo en base64
        $logoPath = public_path('apple-touch-icon.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $imgData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($imgData);
        }

        // Info institucional
        $gimnasio = 'Gimnasio X';
        $empresa = 'Soluciones en Ingeniería T&J';
        $usuario = auth()->check() ? auth()->user()->name : 'Desconocido';
        $fechaGeneracion = now('America/Lima')->format('d/m/Y H:i');

        // Estadísticas
        $total = $methods->count();
        $activos = $methods->where('status', 1)->count();
        $inactivos = $methods->where('status', 0)->count();

        // HTML del PDF
        $html = '
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td style="width:18%;">
                    '.($logoBase64 ? '<img src="'.$logoBase64.'" height="48">' : '').'
                </td>
                <td style="width:70%; text-align:center;">
                    <div style="font-size:24px; font-weight:bold; color:#222;">Informe de Métodos de Pago</div>
                    <div style="font-size:15px; color:#444;">'.$gimnasio.'</div>
                </td>
                <td style="width:12%;"></td>
            </tr>
        </table>
        <div style="text-align:center; font-size:14px; color:#555; margin-bottom:12px;">
            Lista detallada de los métodos de pago registrados en el sistema.
        </div>
        <table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:11px;">
            <thead>
                <tr style="background-color:#2874A6; color:#fff;">
                    <th style="width:10%; text-align:center;">ID</th>
                    <th style="width:30%; text-align:left;">Nombre</th>
                    <th style="width:40%; text-align:left;">Descripción</th>
                    <th style="width:20%; text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($methods as $m) {
            $estadoColor = $m->status ? '#14A44D' : '#D32F2F';
            $estadoText = $m->status ? 'Activo' : 'Inactivo';
            $html .= '
                <tr>
                    <td style="width:10%; text-align:center;">'.$m->id.'</td>
                    <td style="width:30%; text-align:left;">'.htmlspecialchars($m->name).'</td>
                    <td style="width:40%; text-align:left;">'.htmlspecialchars($m->description).'</td>
                    <td style="width:20%; text-align:center; color:'.$estadoColor.'; font-weight:bold;">'.$estadoText.'</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>
        <br>
        <div style="font-size:12px; color:#444;">
            <b>Total:</b> '.$total.' |
            <b>Activos:</b> '.$activos.' |
            <b>Inactivos:</b> '.$inactivos.'
        </div>
        <div style="font-size:11px; color:#666; text-align:right; margin-top:8px;">
            Usuario: <b>'.$usuario.'</b><br>
            Fecha y hora: <b>'.$fechaGeneracion.'</b>
        </div>
        <hr style="margin:12px 0 2px 0; border:none; border-top:1px solid #BBB;">
        <div style="font-size:10px; color:#888; text-align:center; margin-top:6px;">
            Este informe ha sido generado por encargo de <b>'.$empresa.'</b>, responsable del sistema de membresías.<br>
            Todos los derechos reservados &copy; '.date('Y').'
        </div>
        ';

        // Generación del PDF
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Laravel');
        $pdf->SetTitle('Informe de Métodos de Pago');
        $pdf->SetSubject('Reporte de Métodos de Pago');
        $pdf->SetMargins(12, 6, 12, true);
        $pdf->SetAutoPageBreak(true, 18);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        if (ob_get_length()) ob_end_clean();

        return response($pdf->Output('informe_metodos_pago.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="informe_metodos_pago.pdf"');
    }
}

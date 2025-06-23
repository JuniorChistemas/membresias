<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Local;
use TCPDF;

class LocalPDFController extends Controller
{
    public function exportPDF()
    {
        $locals = Local::orderBy('id', 'asc')->get();

        // Logo a la izquierda, si existe
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

        // Encabezado visual: logo a la izquierda, título centrado
        $html = '
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td style="width:18%;">
                    '.($logoBase64 ? '<img src="'.$logoBase64.'" height="48">' : '').'
                </td>
                <td style="width:70%; text-align:center;">
                    <div style="font-size:24px; font-weight:bold; color:#222; margin-bottom:3px;">Informe de Locales</div>
                    <div style="font-size:15px; color:#444; margin-bottom:2px;">'.$gimnasio.'</div>
                </td>
                <td style="width:20%;"></td>
            </tr>
        </table>
        <div style="width:100%; text-align:center; font-size:14px; color:#555; margin-bottom:12px;">
            Reporte detallado de todos los locales registrados en el sistema.
        </div>
        <table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px; table-layout:fixed;">
            <thead>
                <tr style="background-color:#2874A6; color:#fff;">
                    <th style="width:10%; text-align:center;">ID</th>
                    <th style="width:30%; text-align:left;">Nombre</th>
                    <th style="width:40%; text-align:left;">Dirección</th>
                    <th style="width:20%; text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($locals as $local) {
            $estadoColor = $local->status == 1 ? "#14A44D" : "#D32F2F";
            $estadoText = $local->status == 1 ? "Activo" : "Inactivo";
            $html .= '
                <tr>
                    <td style="width:10%; text-align:center;">'.$local->id.'</td>
                    <td style="width:30%;">'.htmlspecialchars($local->name).'</td>
                    <td style="width:40%;">'.htmlspecialchars($local->address).'</td>
                    <td style="width:20%; text-align:center; color:'.$estadoColor.'; font-weight:bold;">'.$estadoText.'</td>
                </tr>
            ';
        }
        $html .= '
            </tbody>
        </table>
        <br>
        <div style="width:100%; font-size:11px; color:#666; text-align:right;">
            Usuario: <b>'.$usuario.'</b><br>
            Fecha y hora: <b>'.$fechaGeneracion.'</b>
        </div>
        <hr style="margin:12px 0 2px 0; border:none; border-top:1px solid #BBB;">
        <div style="font-size:10px; color:#888; text-align:center; margin-top:6px;">
            Este informe ha sido generado por encargo de <b>'.$empresa.'</b>, responsable del sistema de membresías y gestión de locales.<br>
            Todos los derechos reservados &copy; '.date('Y').'
        </div>
        ';

        $pdf = new TCPDF();
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Laravel');
        $pdf->SetTitle('Informe de Locales');
        $pdf->SetSubject('Reporte formal de Locales');
        $pdf->SetMargins(12, 6, 12, true);
        $pdf->SetAutoPageBreak(true, 18);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        if (ob_get_length()) ob_end_clean();

        $pdfOutput = $pdf->Output('informe_locales.pdf', 'S');
        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="informe_locales.pdf"');
    }
}

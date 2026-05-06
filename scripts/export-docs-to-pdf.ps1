# Re-export public/docs/*.docx to PDF using Microsoft Word (Windows).
# Run after editing Word originals: powershell -ExecutionPolicy Bypass -File scripts/export-docs-to-pdf.ps1

$base = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot "..\public\docs"))
$wdExportFormatPDF = 17
$word = New-Object -ComObject Word.Application
$word.Visible = $false
try {
    Get-ChildItem -Path $base -Filter "*.docx" | ForEach-Object {
        $outPath = [System.IO.Path]::ChangeExtension($_.FullName, ".pdf")
        $doc = $word.Documents.Open($_.FullName, $false, $true)
        $doc.ExportAsFixedFormat($outPath, $wdExportFormatPDF)
        $doc.Close($false)
        Write-Host "Exported: $($_.Name) -> $($outPath | Split-Path -Leaf)"
    }
}
finally {
    $word.Quit()
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($word) | Out-Null
    [GC]::Collect()
}

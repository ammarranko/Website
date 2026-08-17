# Prépare le jeu d'images réellement utilisé par le site.
#
# images/incoming/ = originaux sourcés via Openverse (licences dans images/CREDITS.md).
# images/source/   = archive brute récupérée de l'ancien site badr.ca.
# images/web/      = jeu curé et optimisé, seul importé dans WordPress par tools/seed.php.
#
# Usage : powershell -File tools/optimize-images.ps1

Add-Type -AssemblyName System.Drawing

$root     = Split-Path -Parent $PSScriptRoot
$srcOld   = Join-Path $root 'images\source'
$srcNew   = Join-Path $root 'images\incoming'
$dest     = Join-Path $root 'images\web'

New-Item -ItemType Directory -Force -Path $dest | Out-Null

$encoder = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/jpeg' }
$params  = New-Object System.Drawing.Imaging.EncoderParameters 1
$params.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter ([System.Drawing.Imaging.Encoder]::Quality), 82

function Convert-One($inPath, $outPath, $maxW) {
    if (-not (Test-Path $inPath)) { Write-Host ("MANQUANT  " + (Split-Path $inPath -Leaf)); return }

    # Les WebP ne sont pas lisibles par GDI+ : ils sont déjà légers, on copie tel quel.
    $head = [System.IO.File]::ReadAllBytes($inPath)[0..11]
    if ($head[0] -eq 0x52 -and $head[8] -eq 0x57) {
        $webpOut = [System.IO.Path]::ChangeExtension($outPath, '.webp')
        Copy-Item $inPath $webpOut -Force
        Write-Host ("{0,-30} {1,5} Ko  (WebP, copié tel quel)" -f (Split-Path $webpOut -Leaf), [Math]::Round((Get-Item $webpOut).Length/1KB,0))
        return
    }

    $img = [System.Drawing.Image]::FromFile($inPath)
    try {
        $ratio = [Math]::Min(1.0, $maxW / $img.Width)
        $w = [int][Math]::Round($img.Width * $ratio)
        $h = [int][Math]::Round($img.Height * $ratio)
        $bmp = New-Object System.Drawing.Bitmap $w, $h
        $g   = [System.Drawing.Graphics]::FromImage($bmp)
        $g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
        $g.PixelOffsetMode   = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
        $g.SmoothingMode     = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
        $g.Clear([System.Drawing.Color]::White)
        $g.DrawImage($img, 0, 0, $w, $h)
        $g.Dispose()
        $bmp.Save($outPath, $encoder, $params)
        $bmp.Dispose()
        $before = [Math]::Round((Get-Item $inPath).Length/1KB,0)
        $after  = [Math]::Round((Get-Item $outPath).Length/1KB,0)
        Write-Host ("{0,-30} {1,5} Ko -> {2,4} Ko  ({3}x{4})" -f (Split-Path $outPath -Leaf), $before, $after, $w, $h)
    } finally { $img.Dispose() }
}

Write-Host '--- Photos communautaires (Openverse, CC0) ---'
# Espace Familles est volontairement absent : aucune photo retenue ne convenait
# sans réutiliser le même modèle qu'Espace Papas. Ce panneau utilise le visuel
# de marque abstrait prévu par le design.
$new = @(
    @{ in = 'espace-parents.jpg';       out = 'espace-parents.jpg';       maxW = 1600 },
    @{ in = 'espace-papas.jpg';         out = 'espace-papas.jpg';         maxW = 1600 },
    @{ in = 'espace-femmes.jpg';        out = 'espace-femmes.jpg';        maxW = 1600 },
    @{ in = 'espace-filles.jpg';        out = 'espace-filles.jpg';        maxW = 1600 },
    @{ in = 'espace-aines.jpg';         out = 'espace-aines.jpg';         maxW = 1600 },
    @{ in = 'banque-alimentaire.webp';  out = 'banque-alimentaire.jpg';   maxW = 1600 },
    @{ in = 'benevoles-provisions.webp';out = 'benevoles-provisions.jpg'; maxW = 1600 }
)
foreach ($item in $new) { Convert-One (Join-Path $srcNew $item.in) (Join-Path $dest $item.out) $item.maxW }

Write-Host ''
Write-Host '--- Photos conservées de l''ancien site badr.ca ---'
# Photos génériques volontairement écartées : champ de cosmos, ampoule clipart,
# réunion d'affaires, Vieux-Québec, captures d'écran, formes décoratives.
$old = @(
    @{ in = 'cropped-cropped-hands-1-scaled-1.jpg';                                                                                        out = 'communaute-mains.jpg';      maxW = 2000 },
    @{ in = 'close-up-people-volunteer-teamwork-putting-finger-star-shapehands-togetherstack-handsunity-teamwork-world-environment-day-scaled.jpg'; out = 'benevolat-mains.jpg';   maxW = 2000 },
    @{ in = 'little-kids-playing-toys-learning-center-3-scaled.png';                                                                        out = 'espace-petite-enfance.jpg'; maxW = 1400 },
    @{ in = 'little-kids-playing-toys-learning-center-1-scaled.png';                                                                        out = 'espace-enfants.jpg';        maxW = 1400 },
    @{ in = 'little-kids-playing-toys-learning-center-scaled.png';                                                                          out = 'espace-jeunes.jpg';         maxW = 1600 }
)
foreach ($item in $old) { Convert-One (Join-Path $srcOld $item.in) (Join-Path $dest $item.out) $item.maxW }

$total = [Math]::Round((Get-ChildItem $dest -File | Measure-Object -Property Length -Sum).Sum / 1KB, 0)
Write-Host ''
Write-Host ("Total images/web : {0} Ko sur {1} fichiers" -f $total, (Get-ChildItem $dest -File).Count)

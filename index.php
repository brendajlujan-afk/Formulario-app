<?php
declare(strict_types=1);
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
if (empty($_SESSION['public_form_csrf'])) {
    $_SESSION['public_form_csrf'] = bin2hex(random_bytes(24));
}
$csrf = htmlspecialchars($_SESSION['public_form_csrf'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$success = isset($_GET['enviado']) && $_GET['enviado'] === '1';
$failed = isset($_GET['error']) && $_GET['error'] === '1';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#EFF0F6">
  <meta name="robots" content="noindex, nofollow">
  <meta name="description" content="Sumate al equipo de Producción de laCatedral. Completá tus datos para que podamos conocerte.">
  <title>Formulario público | laCatedral PRODUCCIÓN</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root{
      --smoky:#EFF0F6; --lavender:#D9B8F3; --lime:#DFF37D; --blue:#4558C8;
      --orange:#EE5E37; --orange-main:#FF5120; --ink:#292B2D; --muted:#747780;
      --stroke:#D9DAE3; --paper:#FCFAFB; --green:#24451B;
    }
    *{box-sizing:border-box}
    html{min-height:100%;background:var(--paper)}
    body{margin:0;min-height:100svh;background:var(--paper);color:var(--ink);font:400 15px/1.5 Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
    button,input,select{font:inherit}
    a{color:inherit}
    .wrap{max-width:1120px;margin:0 auto;padding:0 28px}
    .topbar{background:#ECECEC;border-bottom:1px solid #E3E3E7}
    .topbar-inner{min-height:76px;display:flex;align-items:center;justify-content:space-between;gap:20px}
    .brand{color:var(--green);font-size:24px;font-weight:800;letter-spacing:-.075em;white-space:nowrap;line-height:1}
    .brand span{font-weight:500;letter-spacing:-.075em}
    .brand-sub{color:#626570;letter-spacing:.18em;font-weight:700;font-size:9px;margin-top:5px}
    .top-label{display:flex;align-items:center;gap:8px;background:#F9F9FC;border:1px solid #E5E5E9;border-radius:999px;padding:10px 15px;color:#51545C;font-size:12px;font-weight:700;white-space:nowrap}
    .top-label svg{width:15px;height:15px;color:var(--blue)}
    .content{padding:56px 0 68px}
    .eyebrow{font-size:11px;font-weight:800;letter-spacing:.16em;color:var(--blue);text-transform:uppercase;margin:0 0 12px}
    h1{font-size:clamp(31px,4.5vw,49px);line-height:1.1;letter-spacing:-.055em;font-weight:750;margin:0 0 12px;color:#1D1F23}
    h1 span{color:var(--blue)}
    .lead{font-size:clamp(14px,1.9vw,17px);color:var(--muted);margin:0;max-width:700px;line-height:1.65}
    .form-card{margin-top:32px;background:#fff;border:1px solid var(--stroke);border-radius:26px;box-shadow:0 12px 38px rgba(41,43,45,.045);padding:clamp(22px,4vw,48px)}
    .form-head{display:flex;align-items:center;gap:14px;margin-bottom:30px}
    .form-head-icon{display:grid;place-items:center;flex:0 0 48px;width:48px;height:48px;background:var(--lime);color:var(--ink);border-radius:15px}
    .form-head-icon svg{width:22px;height:22px}
    h2{font-size:22px;line-height:1.2;letter-spacing:-.035em;margin:0;font-weight:750}
    .form-head p{margin:4px 0 0;color:var(--muted);font-size:13px}
    form{display:block}
    fieldset{margin:0;padding:0;border:0}
    fieldset + fieldset{margin-top:31px;padding-top:30px;border-top:1px solid #EBEBEF}
    legend{float:left;width:100%;font-size:15px;font-weight:800;letter-spacing:-.02em;margin-bottom:17px;display:flex;align-items:center;gap:9px}
    legend .mark{display:inline-flex;align-items:center;justify-content:center;min-width:29px;height:29px;border-radius:9px;background:var(--smoky);font-weight:750;font-size:12px}
    .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:17px;clear:both}
    .field{display:block;min-width:0}
    .field.wide{grid-column:1/-1}
    .field-label{display:block;font-size:12px;font-weight:700;color:#42454A;margin-bottom:7px}
    .optional{color:#8A8D94;font-weight:500}
    input,select{width:100%;height:53px;padding:0 16px;background:#F7F8FB;border:1px solid #DBDFE8;border-radius:13px;outline:0;color:var(--ink);font-size:14px;transition:border-color .18s ease,box-shadow .18s ease}
    input::placeholder{color:#9A9CA4;opacity:1}
    input:hover,select:hover{border-color:#B4B9C8}
    input:focus,select:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(69,88,200,.12);background:#FFF}
    select{appearance:none;-webkit-appearance:none;padding-right:44px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%2351545C' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 15px center}
    select:invalid{color:#8A8D94}
    .submitbar{margin-top:30px;display:flex;flex-direction:column;gap:14px}
    button[type=submit]{appearance:none;border:0;background:var(--orange-main);color:#fff;border-radius:14px;min-height:56px;display:flex;align-items:center;justify-content:center;gap:12px;padding:13px 22px;font-size:15px;font-weight:800;cursor:pointer;transition:background .18s,transform .18s,box-shadow .18s}
    button[type=submit]:hover{background:#E34217;box-shadow:0 8px 15px rgba(255,81,32,.16)}
    button[type=submit]:active{transform:translateY(1px)}
    button[type=submit]:focus-visible{outline:3px solid var(--blue);outline-offset:3px}
    button[type=submit] svg{width:18px;height:18px}
    .notice{text-align:center;color:#777A83;font-size:11px;line-height:1.6;max-width:670px;margin:0 auto}
    .feedback{margin-bottom:24px;border-radius:14px;padding:15px 19px;font-size:14px;line-height:1.6}
    .feedback.ok{background:#F2FADB;border:1px solid #D5EA89;color:#29401F}
    .feedback.err{background:#FFF2EE;border:1px solid #F5C7B9;color:#873A22}
    .trap{position:absolute;left:-99999px;width:1px;height:1px;overflow:hidden}
    footer{color:#9798A1;text-align:center;font-size:11px;padding:0 20px 28px}
    @media(max-width:680px){
      .wrap{padding:0 17px}.topbar-inner{min-height:68px}
      .brand{font-size:21px}.top-label{font-size:10px;padding:8px 11px}
      .content{padding:34px 0 46px}.eyebrow{font-size:10px}h1{font-size:34px}.lead{font-size:14px}
      .form-card{margin-top:24px;border-radius:22px;padding:21px 17px}.form-head{margin-bottom:25px;gap:10px}
      .form-head-icon{flex-basis:42px;width:42px;height:42px;border-radius:12px}
      h2{font-size:19px}.form-head p{font-size:12px}
      .form-grid{grid-template-columns:1fr;gap:15px}.field.wide{grid-column:1}
      fieldset+fieldset{margin-top:24px;padding-top:24px}
      .submitbar{margin-top:26px}
      input,select{font-size:16px} /* evita zoom al tocar campos en iOS */
    }
    @media(prefers-reduced-motion:reduce){*{transition:none!important}}
  </style>
</head>
<body>
  <div class="topbar">
    <div class="wrap topbar-inner">
      <div aria-label="laCatedral PRODUCCIÓN">
        <div class="brand"><span>la</span>Catedral</div>
        <div class="brand-sub">PRODUCCIÓN</div>
      </div>
      <div class="top-label">
        <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4"/><path d="m13 3 8 0 0 8"/><path d="m21 3-10 10"/></svg>
        Formulario público
      </div>
    </div>
  </div>
  <main class="wrap content">
    <p class="eyebrow">La Catedral · Equipo de Producción</p>
    <h1>Sumate a <span>Producción.</span></h1>
    <p class="lead">¡Hola! Si querés formar parte del equipo, completá este formulario. Nos gustaría conocerte y saber en qué área te gustaría servir.</p>
    <section class="form-card" aria-label="Formulario para sumarte a Producción">
      <div class="form-head">
        <div class="form-head-icon"><svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M20 8v6m-3-3h6"/></svg></div>
        <div><h2>Formulario de inscripción</h2><p>Completá los datos para enviar tu solicitud.</p></div>
      </div>
      <?php if ($success): ?>
      <div class="feedback ok" role="status"><strong>¡Formulario enviado!</strong> Recibimos tus datos. Muchas gracias por sumarte.</div>
      <?php elseif ($failed): ?>
      <div class="feedback err" role="alert">No pudimos guardar el formulario. Revisá tus datos e intentá nuevamente.</div>
      <?php endif; ?>
      <form action="enviar.php" method="post" accept-charset="UTF-8">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <div class="trap" aria-hidden="true"><label>Dejá este campo vacío <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <fieldset>
          <legend><span class="mark">01</span> Información personal</legend>
          <div class="form-grid">
            <label class="field"><span class="field-label">Nombre y apellido</span><input name="nombre" required type="text" maxlength="120" autocomplete="name" placeholder="Escribí tu nombre completo"></label>
            <label class="field"><span class="field-label">Edad</span><input name="edad" required type="number" min="12" max="100" inputmode="numeric" placeholder="Tu edad"></label>
          </div>
        </fieldset>
        <fieldset>
          <legend><span class="mark">02</span> Tu recorrido</legend>
          <div class="form-grid">
            <label class="field"><span class="field-label">¿Hace cuánto te congregás?</span><input name="congregacion" required type="text" maxlength="100" placeholder="Ej. 3 años"></label>
            <label class="field"><span class="field-label">Líder a cargo</span><input name="lider" required type="text" maxlength="120" placeholder="Nombre de tu líder"></label>
            <label class="field"><span class="field-label">¿Sos líder?</span><select name="sos_lider" required><option value="" selected disabled>Seleccioná una opción</option><option value="Sí">Sí</option><option value="No">No</option></select></label>
            <label class="field"><span class="field-label">Módulo de la escuela de líderes <span class="optional">(opcional)</span></span><input name="modulo" type="text" maxlength="100" placeholder="Ej. Módulo 2"></label>
          </div>
        </fieldset>
        <fieldset>
          <legend><span class="mark">03</span> Área de Producción</legend>
          <div class="form-grid">
            <label class="field wide"><span class="field-label">¿En qué área te gustaría servir?</span><select name="area" required><option value="" selected disabled>Elegí un área de Producción</option><option value="Sonido">Sonido</option><option value="Luces">Luces</option><option value="Pantallas">Pantallas</option><option value="Transmisiones">Transmisiones</option><option value="Stage">Stage</option><option value="Cámaras">Cámaras</option></select></label>
          </div>
        </fieldset>
        <div class="submitbar">
          <button type="submit">Enviar formulario <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></button>
          <p class="notice">Tus datos se utilizarán para gestionar tu solicitud de incorporación al equipo de Producción. No necesitás una cuenta para enviar este formulario.</p>
        </div>
      </form>
    </section>
  </main>
  <footer>laCatedral · PRODUCCIÓN</footer>
</body>
</html>

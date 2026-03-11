<?php
include '../partials/start.php';
require_once __DIR__ . "/../db/config.php";

$error = '';

// Original logic from user's file
if($_POST){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!$username || !$password){
        $error = "Iltimos, barcha maydonlarni to'ldiring.";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if($user){
            $error = "Bu foydalanuvchi nomi band.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute([$username, $hash]);
            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PC STORE — Ro'yxatdan o'tish</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#030508;
  --surface:#07090f;
  --surface2:#0b0e18;
  --border:#141d35;
  --border2:#1c2844;
  --cyan:#00ffcc;
  --cyan2:#00b8e6;
  --purple:#8b5cf6;
  --pink:#f43f5e;
  --text:#b0c4e8;
  --text2:#485f88;
  --white:#eef4ff;
}

html,body{height:100%;overflow:hidden}
body{

margin-top:100px;
  background:var(--bg);
  font-family:'Space Mono',monospace;
  color:var(--text);
  display:flex;align-items:center;justify-content:center;
  position:relative;
}

/* ── CANVAS ── */
#cv{position:fixed;inset:0;z-index:0;pointer-events:none}

/* ── NOISE OVERLAY ── */
body::after{
  content:'';position:fixed;inset:0;
  background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='f'%3E%3CfeTurbulence baseFrequency='.85' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23f)' opacity='.032'/%3E%3C/svg%3E");
  opacity:.5;pointer-events:none;z-index:1;
}

/* ── GRID ── */
.grid{
  position:fixed;inset:0;z-index:0;pointer-events:none;
  background:
    linear-gradient(rgba(139,92,246,.022) 1px,transparent 1px),
    linear-gradient(90deg,rgba(139,92,246,.022) 1px,transparent 1px);
  background-size:52px 52px;
  animation:gp 30s linear infinite;
}
@keyframes gp{to{background-position:52px 52px}}

/* ── ORBS ── */
.orb{position:fixed;border-radius:50%;pointer-events:none;filter:blur(100px);z-index:0}
.o1{width:580px;height:580px;
  background:radial-gradient(circle,rgba(139,92,246,.12),transparent 68%);
  top:-200px;left:-120px;animation:oa 14s ease-in-out infinite}
.o2{width:480px;height:480px;
  background:radial-gradient(circle,rgba(0,255,204,.09),transparent 68%);
  bottom:-160px;right:-100px;animation:ob 17s ease-in-out infinite}
.o3{width:300px;height:300px;
  background:radial-gradient(circle,rgba(244,63,94,.07),transparent 68%);
  top:45%;right:35%;animation:oc 11s ease-in-out infinite}
@keyframes oa{0%,100%{transform:translate(0,0)}50%{transform:translate(30px,-28px)}}
@keyframes ob{0%,100%{transform:translate(0,0)}50%{transform:translate(-26px,32px)}}
@keyframes oc{0%,100%{transform:translate(0,0)}50%{transform:translate(14px,-14px)}}

/* ── MAIN LAYOUT ── */
.wrap{
  position:relative;z-index:10;
  display:grid;
  grid-template-columns:1fr auto 400px;
  width:min(1060px,96vw);
  background:var(--surface);
  border:1px solid var(--border2);
  box-shadow:
    0 0 0 1px rgba(139,92,246,.05),
    0 50px 140px rgba(0,0,0,.88),
    inset 0 1px 0 rgba(255,255,255,.03);
  overflow:hidden;
  animation:wIn .75s cubic-bezier(.16,1,.3,1) both;
}
@keyframes wIn{from{opacity:0;transform:scale(.95) translateY(22px)}to{opacity:1;transform:none}}

/* clipped top-right & bottom-left */
.wrap::before{
  content:'';position:absolute;top:0;right:0;
  width:0;height:0;
  border-top:28px solid var(--bg);
  border-left:28px solid transparent;
  z-index:30;
}
.wrap::after{
  content:'';position:absolute;bottom:0;left:0;
  width:0;height:0;
  border-bottom:28px solid var(--bg);
  border-right:28px solid transparent;
  z-index:30;
}

/* ═══════════════ LEFT — INFO PANEL ═══════════════ */
.lp{
  padding:52px 48px;
  display:flex;flex-direction:column;justify-content:space-between;
  background:linear-gradient(145deg,var(--surface2),var(--surface));
  position:relative;overflow:hidden;
}

/* downward scan beam */
.lp::before{
  content:'';position:absolute;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,rgba(139,92,246,.55),transparent);
  animation:scanDown 5s ease-in-out infinite;z-index:1;pointer-events:none;
}
@keyframes scanDown{0%{top:0;opacity:.9}100%{top:100%;opacity:0}}

.logo{display:flex;align-items:center;gap:14px;animation:fu .7s .1s both}
.hex{
  width:46px;height:46px;flex-shrink:0;
  background:linear-gradient(135deg,var(--purple),var(--cyan));
  clip-path:polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);
  display:flex;align-items:center;justify-content:center;font-size:20px;
  animation:hpulse 3s ease-in-out infinite;
}
@keyframes hpulse{
  0%,100%{filter:drop-shadow(0 0 6px rgba(139,92,246,.45))}
  50%{filter:drop-shadow(0 0 20px rgba(139,92,246,.9))}
}
.logo-t{font-family:'Syne',sans-serif;font-size:24px;font-weight:800;letter-spacing:3px;color:var(--white)}
.logo-t span{background:linear-gradient(90deg,var(--purple),var(--cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.logo-s{font-size:9px;letter-spacing:5px;text-transform:uppercase;color:var(--text2);margin-left:60px;margin-top:4px;animation:fu .7s .15s both}

.big-h{
  font-family:'Syne',sans-serif;
  font-size:clamp(26px,3vw,40px);font-weight:800;
  line-height:1.1;color:var(--white);
  animation:fu .7s .25s both;
}
.big-h .g{
  display:block;
  background:linear-gradient(90deg,var(--purple),var(--cyan2),var(--cyan));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-size:200%;animation:shimmer 4s linear infinite;
}
@keyframes shimmer{0%{background-position:0%}100%{background-position:200%}}

.sub{font-size:11px;color:var(--text2);line-height:1.85;margin-top:14px;max-width:340px;animation:fu .7s .35s both}

/* perks */
.perks{display:flex;flex-direction:column;gap:13px;animation:fu .7s .45s both}
.perk{
  display:flex;align-items:flex-start;gap:12px;
  padding:13px;border:1px solid var(--border);
  background:rgba(255,255,255,.015);
  transition:border-color .3s,background .3s;
}
.perk:hover{border-color:rgba(139,92,246,.3);background:rgba(139,92,246,.05)}
.pico{
  width:30px;height:30px;flex-shrink:0;
  background:rgba(139,92,246,.12);
  display:flex;align-items:center;justify-content:center;
  color:var(--purple);
}
.pt{font-family:'Syne',sans-serif;font-size:12px;font-weight:700;color:var(--white);margin-bottom:2px}
.pd{font-size:10px;color:var(--text2);line-height:1.5}

.feats{display:flex;flex-direction:column;gap:8px;animation:fu .7s .55s both}
.feat{display:flex;align-items:center;gap:10px;font-size:10px;color:var(--text2)}
.fd{width:5px;height:5px;border-radius:50%;flex-shrink:0;animation:blink 2s ease-in-out infinite}
.fd1{background:var(--purple);box-shadow:0 0 8px var(--purple)}
.fd2{background:var(--cyan);box-shadow:0 0 8px var(--cyan);animation-delay:.4s}
.fd3{background:var(--cyan2);box-shadow:0 0 8px var(--cyan2);animation-delay:.8s}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}

/* ── VERTICAL DIVIDER ── */
.vd{
  width:1px;flex-shrink:0;
  background:linear-gradient(to bottom,
    transparent 5%,
    var(--border2) 20%,
    rgba(139,92,246,.25) 50%,
    var(--border2) 80%,
    transparent 95%);
}

/* ═══════════════ RIGHT — FORM PANEL ═══════════════ */
.rp{
  padding:44px 40px;
  display:flex;flex-direction:column;justify-content:center;
  position:relative;overflow:hidden;
}

/* upward scan */
.rp::before{
  content:'';position:absolute;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,rgba(0,255,204,.45),transparent);
  animation:scanUp 6s ease-in-out infinite 2s;z-index:1;pointer-events:none;
}
@keyframes scanUp{0%{bottom:0;opacity:.8}100%{bottom:100%;opacity:0}}

/* top accent */
.rp::after{
  content:'';position:absolute;top:0;left:15%;right:15%;height:2px;
  background:linear-gradient(90deg,transparent,var(--purple),var(--cyan),transparent);
  animation:tglow 3s ease-in-out infinite;
}
@keyframes tglow{0%,100%{opacity:.35}50%{opacity:1}}

/* corner marks */
.ctop{position:absolute;top:14px;right:14px;width:16px;height:16px;border-top:2px solid rgba(139,92,246,.6);border-right:2px solid rgba(139,92,246,.6)}
.cbot{position:absolute;bottom:14px;left:14px;width:16px;height:16px;border-bottom:2px solid rgba(0,255,204,.5);border-left:2px solid rgba(0,255,204,.5)}

/* ─ STEPS ─ */
.steps{
  display:flex;align-items:center;
  margin-bottom:28px;padding-bottom:22px;
  border-bottom:1px solid var(--border);
  animation:fu .6s .1s both;
}
.step{display:flex;align-items:center;gap:7px;font-size:9px;letter-spacing:2px;text-transform:uppercase}
.sn{
  width:22px;height:22px;border:1px solid var(--border2);
  display:flex;align-items:center;justify-content:center;
  font-family:'Syne',sans-serif;font-size:11px;font-weight:700;flex-shrink:0;
}
.step.act .sn{background:var(--cyan);border-color:var(--cyan);color:#000;box-shadow:0 0 14px rgba(0,255,204,.4)}
.step.act span{color:var(--cyan)}
.step:not(.act) span{color:var(--text2)}
.sline{flex:1;height:1px;background:var(--border);margin:0 10px}

/* ─ HEAD ─ */
.fh{animation:fu .7s .2s both;margin-bottom:26px}
.ftag{
  font-size:9px;letter-spacing:4px;text-transform:uppercase;
  color:var(--cyan);margin-bottom:10px;
  display:flex;align-items:center;gap:8px;
}
.ftag::before{content:'';width:20px;height:1px;background:linear-gradient(90deg,var(--cyan),transparent)}
.ftitle{font-family:'Syne',sans-serif;font-size:25px;font-weight:800;color:var(--white)}
.fsub{font-size:11px;color:var(--text2);margin-top:5px;line-height:1.6}

/* ─ ERROR ─ */
.err{
  background:rgba(244,63,94,.07);
  border:1px solid rgba(244,63,94,.2);
  border-left:3px solid var(--pink);
  padding:11px 15px;font-size:11px;color:#fb7185;
  margin-bottom:18px;
  display:flex;align-items:center;gap:10px;
  animation:shk .4s both;
}
@keyframes shk{0%{transform:translateX(-8px);opacity:0}50%{transform:translateX(4px)}100%{transform:translateX(0);opacity:1}}

/* ─ FIELDS ─ */
.fg{margin-bottom:18px;animation:fu .6s both}
.fg1{animation-delay:.3s}.fg2{animation-delay:.4s}

.fl{
  display:flex;align-items:center;justify-content:space-between;
  font-size:9px;letter-spacing:3px;text-transform:uppercase;
  color:var(--text2);margin-bottom:8px;
}
.ftag2{font-size:8px;letter-spacing:1px;color:var(--border2);border:1px solid var(--border);padding:1px 5px}

.shell{position:relative}
.shell::before{
  content:'';position:absolute;top:0;left:0;bottom:0;width:3px;
  background:linear-gradient(to bottom,var(--cyan),var(--purple));
  opacity:0;transition:opacity .3s;
}
.shell:focus-within::before{opacity:1}

.ico{
  position:absolute;left:14px;top:50%;transform:translateY(-50%);
  width:15px;height:15px;color:var(--text2);
  transition:color .3s;pointer-events:none;
}
.shell:focus-within .ico{color:var(--cyan)}

.fi{
  width:100%;
  background:rgba(0,255,204,.025);
  border:1px solid var(--border);
  color:var(--white);
  padding:14px 14px 14px 42px;
  font-family:'Space Mono',monospace;font-size:13px;
  outline:none;transition:all .3s;
  clip-path:polygon(0 0,calc(100% - 10px) 0,100% 10px,100% 100%,0 100%);
}
.fi::placeholder{color:var(--text2)}
.fi:focus{
  border-color:rgba(0,255,204,.35);
  background:rgba(0,255,204,.04);
  box-shadow:0 0 0 1px rgba(0,255,204,.12),0 0 24px rgba(0,255,204,.05);
}
.fline{
  height:1px;
  background:linear-gradient(90deg,var(--cyan),var(--purple));
  transform:scaleX(0);transform-origin:left;
  transition:transform .4s cubic-bezier(.4,0,.2,1);
  margin-top:-1px;
}
.shell:focus-within .fline{transform:scaleX(1)}

.eye{
  position:absolute;right:13px;top:50%;transform:translateY(-50%);
  background:none;border:none;color:var(--text2);
  cursor:pointer;padding:4px;z-index:2;transition:color .3s;
}
.eye:hover{color:var(--cyan)}

/* ─ PASSWORD STRENGTH ─ */
.pstr{display:flex;gap:3px;margin-top:7px}
.pb{flex:1;height:2px;background:var(--border);border-radius:2px;transition:background .3s,box-shadow .3s}
.pw{background:var(--pink)}
.pm{background:#f59e0b}
.ps{background:var(--cyan);box-shadow:0 0 6px rgba(0,255,204,.4)}
.pslbl{font-size:9px;color:var(--text2);margin-top:4px;letter-spacing:1px;text-transform:uppercase;height:12px}

/* ─ TERMS ─ */
.trow{
  display:flex;align-items:flex-start;gap:10px;
  margin-bottom:22px;
  animation:fu .6s .5s both;
}
.cbox{
  width:15px;height:15px;border:1px solid var(--border2);
  background:transparent;appearance:none;cursor:pointer;
  position:relative;transition:all .3s;margin-top:2px;flex-shrink:0;
}
.cbox:checked{background:var(--cyan);border-color:var(--cyan);box-shadow:0 0 10px rgba(0,255,204,.4)}
.cbox:checked::after{content:'✓';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:8px;font-weight:700;color:#000}
.tlbl{font-size:10px;color:var(--text2);line-height:1.6;cursor:pointer}
.tlbl a{color:var(--cyan);text-decoration:none;border-bottom:1px solid transparent;transition:border-color .3s}
.tlbl a:hover{border-color:var(--cyan)}

/* ─ SUBMIT ─ */
.sbtn{
  width:100%;padding:15px;
  background:linear-gradient(135deg,var(--cyan),var(--cyan2),var(--purple));
  color:#000;border:none;
  font-family:'Syne',sans-serif;font-size:13px;font-weight:800;
  letter-spacing:4px;text-transform:uppercase;cursor:pointer;
  clip-path:polygon(0 0,calc(100% - 12px) 0,100% 12px,100% 100%,12px 100%,0 calc(100% - 12px));
  position:relative;overflow:hidden;
  transition:transform .25s,box-shadow .25s;
  margin-bottom:22px;
  animation:fu .6s .55s both;
}
.sbtn::before{
  content:'';position:absolute;top:0;left:-100%;
  width:100%;height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.28),transparent);
  transition:left .5s;
}
.sbtn:hover::before{left:100%}
.sbtn:hover{transform:translateY(-2px);box-shadow:0 10px 40px rgba(0,255,204,.32)}
.sbtn:active{transform:translateY(0)}
.btxt{position:relative;z-index:1;display:flex;align-items:center;justify-content:center;gap:10px}
.spin{width:13px;height:13px;border:2px solid rgba(0,0,0,.25);border-top-color:#000;border-radius:50%;animation:sp .7s linear infinite;display:none}
@keyframes sp{to{transform:rotate(360deg)}}

/* ─ BOTTOM ─ */
.or{display:flex;align-items:center;gap:12px;margin-bottom:16px;animation:fu .6s .6s both}
.or::before,.or::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent)}
.ort{font-size:9px;letter-spacing:3px;text-transform:uppercase;color:var(--text2)}

.ll{text-align:center;font-size:11px;color:var(--text2);animation:fu .6s .65s both}
.ll a{color:var(--cyan);text-decoration:none;font-weight:700;letter-spacing:1px;position:relative}
.ll a::after{content:'';position:absolute;bottom:-2px;left:0;width:0;height:1px;background:linear-gradient(90deg,var(--cyan),var(--purple));transition:width .3s}
.ll a:hover::after{width:100%}

.sbar{position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--cyan),var(--cyan2),var(--purple));animation:sbIn .8s .9s both;transform-origin:left}
@keyframes sbIn{from{transform:scaleX(0)}to{transform:scaleX(1)}}

@keyframes fu{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

/* ─ RESPONSIVE ─ */
@media(max-width:800px){
  .lp,.vd{display:none}
  .wrap{grid-template-columns:1fr}
  .rp{padding:44px 28px}
}
</style>
</head>
<body>

<canvas id="cv"></canvas>
<div class="grid"></div>
<div class="orb o1"></div>
<div class="orb o2"></div>
<div class="orb o3"></div>

<div class="wrap">

  <!-- ═══ LEFT INFO ═══ -->
  <div class="lp">
    <div>
      <div class="logo">
        <div class="hex">⬡</div>
        <div class="logo-t">PC<span>STORE</span></div>
      </div>
      <div class="logo-s">Premium Tech — Toshkent</div>
    </div>

    <div>
      <h2 class="big-h">
        Hisob oching —
        <span class="g">afzalliklardan foydalaning</span>
      </h2>
      <p class="sub">
        // Shaxsiy kabinet,<br>
        // buyurtmalar tarixi,<br>
        // maxsus chegirmalar.
      </p>
    </div>

    <div class="perks">
      <div class="perk">
        <div class="pico">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
          </svg>
        </div>
        <div><div class="pt">Maxsus chegirmalar</div><div class="pd">A'zolar uchun 10–20% chegirma</div></div>
      </div>
      <div class="perk">
        <div class="pico">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <div><div class="pt">Buyurtmalar tarixi</div><div class="pd">Barcha xaridlaringizni kuzating</div></div>
      </div>
      <div class="perk">
        <div class="pico">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>
        <div><div class="pt">Sevimlilar ro'yxati</div><div class="pd">Mahsulotlarni keyinroq saqlang</div></div>
      </div>
    </div>

    <div class="feats">
      <div class="feat"><div class="fd fd1"></div>Rasmiy distribyutor — Dell, ASUS, Lenovo</div>
      <div class="feat"><div class="fd fd2"></div>24 soat ichida yetkazib berish</div>
      <div class="feat"><div class="fd fd3"></div>Onlayn texnik yordam — 7/24</div>
    </div>
  </div>

  <!-- ═══ VERTICAL DIVIDER ═══ -->
  <div class="vd"></div>

  <!-- ═══ RIGHT FORM ═══ -->
  <div class="rp">
    <div class="ctop"></div>
    <div class="cbot"></div>

    <!-- Steps -->
    <div class="steps">
      <div class="step act">
        <div class="sn">1</div>
        <span>Hisob</span>
      </div>
      <div class="sline"></div>
      <div class="step">
        <div class="sn">2</div>
        <span>Tasdiqlash</span>
      </div>
      <div class="sline"></div>
      <div class="step">
        <div class="sn">3</div>
        <span>Tayyor</span>
      </div>
    </div>

    <div class="fh">
      <div class="ftag">// yangi hisob</div>
      <div class="ftitle">Ro'yxatdan o'ting</div>
      <div class="fsub">Bepul hisob oching va xarid boshlang</div>
    </div>

    <!-- ERROR -->
    <?php if($error): ?>
    <div class="err">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="post" id="frm">

      <!-- Username -->
      <div class="fg fg1">
        <div class="fl"><span>Foydalanuvchi nomi</span><span class="ftag2">REQ</span></div>
        <div class="shell">
          <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          <input class="fi" type="text" name="username" placeholder="username" required
            autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
          <div class="fline"></div>
        </div>
      </div>

      <!-- Password -->
      <div class="fg fg2">
        <div class="fl"><span>Parol</span><span class="ftag2">REQ</span></div>
        <div class="shell">
          <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
          <input class="fi" type="password" id="pi" name="password" placeholder="••••••••••"
            required autocomplete="new-password" oninput="chkStr(this.value)">
          <div class="fline"></div>
          <button type="button" class="eye" onclick="tgl()">
            <svg id="eico" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
        <!-- strength bars -->
        <div class="pstr">
          <div class="pb" id="b1"></div>
          <div class="pb" id="b2"></div>
          <div class="pb" id="b3"></div>
          <div class="pb" id="b4"></div>
        </div>
        <div class="pslbl" id="slbl"></div>
      </div>

      <!-- Terms -->
      <div class="trow">
        <input type="checkbox" class="cbox" id="tc" required>
        <label class="tlbl" for="tc">
          <a href="#">Foydalanish shartlari</a> va
          <a href="#">Maxfiylik siyosati</a>ga roziman
        </label>
      </div>

      <button type="submit" class="sbtn" id="sb">
        <span class="btxt">
          <span class="spin" id="sp"></span>
          <span id="bl">▶ HISOB YARATISH</span>
        </span>
      </button>
    </form>

    <div class="or"><span class="ort">yoki</span></div>
    <p class="ll">Hisobingiz bormi? — <a href="login.php">Kirish</a></p>
    <div class="sbar"></div>
  </div>

</div>

<script>
/* ── PARTICLES ── */
(()=>{
  const c=document.getElementById('cv'),x=c.getContext('2d');
  let W,H,P=[];
  const sz=()=>{W=c.width=innerWidth;H=c.height=innerHeight};
  sz();addEventListener('resize',sz);
  const r=(a,b)=>a+(b-a)*Math.random();
  for(let i=0;i<65;i++)
    P.push({x:r(0,1),y:r(0,1),vx:r(-.2,.2),vy:r(-.35,-.08),
      rad:r(.4,1.8),life:r(0,1),spd:r(.003,.009),
      c:Math.random()>.5?'139,92,246':'0,255,204'});
  const draw=()=>{
    x.clearRect(0,0,W,H);
    P.forEach(p=>{
      p.life+=p.spd;
      if(p.life>1){p.life=0;p.x=r(0,1);p.y=1.05}
      const a=Math.sin(p.life*Math.PI)*.44;
      x.beginPath();
      x.arc(p.x*W,(p.y-p.life*.25)*H,p.rad,0,Math.PI*2);
      x.fillStyle=`rgba(${p.c},${a})`;
      x.shadowBlur=5;x.shadowColor=`rgba(${p.c},.22)`;
      x.fill();
    });
    requestAnimationFrame(draw);
  };
  draw();
})();

/* ── PASSWORD TOGGLE ── */
function tgl(){
  const i=document.getElementById('pi');
  i.type=i.type==='password'?'text':'password';
}

/* ── PASSWORD STRENGTH ── */
function chkStr(v){
  const bs=[document.getElementById('b1'),document.getElementById('b2'),
            document.getElementById('b3'),document.getElementById('b4')];
  const lbl=document.getElementById('slbl');
  bs.forEach(b=>{b.className='pb'});
  if(!v){lbl.textContent='';return}
  let s=0;
  if(v.length>=6)s++;
  if(v.length>=10)s++;
  if(/[A-Z]/.test(v)&&/[0-9]/.test(v))s++;
  if(/[^A-Za-z0-9]/.test(v))s++;
  const cls=['pw','pw','pm','ps'];
  const labs=['Juda zaif','Zaif',"O'rtacha",'Kuchli'];
  const cols=['#f43f5e','#f97316','#f59e0b','#00ffcc'];
  for(let i=0;i<s;i++)bs[i].classList.add(cls[s-1]);
  lbl.textContent=labs[s-1]||'';
  lbl.style.color=cols[s-1]||'';
}

/* ── SUBMIT LOADING ── */
document.getElementById('frm').addEventListener('submit',()=>{
  document.getElementById('sp').style.display='block';
  document.getElementById('bl').textContent='Yaratilmoqda...';
  document.getElementById('sb').disabled=true;
});
</script>

<?php include '../partials/end.php'; ?>
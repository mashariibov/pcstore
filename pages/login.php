<?php
include '../partials/start.php';
require_once __DIR__ . "/../db/config.php";

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';


    if (!$username || !$password) {
        $error = "Barcha maydonlarni to'ldiring.";
    } 
    
    
    else {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();



        if ($user && password_verify($password, $user['password'])) {
            session_start();


            $_SESSION['user'] = $user;
          if($user['is_admin'] === 1) {
            header("Location: /admin/dashboard.php");
          
        } else {
       header("Location: /index.php");
        }

    }

    }
}



?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PC STORE — Kirish</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
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

/* CANVAS */
#cv{position:fixed;inset:0;z-index:0;pointer-events:none}

/* NOISE */
body::after{
  content:'';position:fixed;inset:0;
  background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='f'%3E%3CfeTurbulence baseFrequency='.9' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23f)' opacity='.035'/%3E%3C/svg%3E");
  opacity:.5;pointer-events:none;z-index:1;
}

/* ORBS */
.orb{position:fixed;border-radius:50%;pointer-events:none;filter:blur(90px);z-index:0}
.orb1{width:550px;height:550px;background:radial-gradient(circle,rgba(0,255,204,.1),transparent 68%);top:-180px;right:-80px;animation:o1 14s ease-in-out infinite}
.orb2{width:480px;height:480px;background:radial-gradient(circle,rgba(139,92,246,.09),transparent 68%);bottom:-140px;left:-100px;animation:o2 17s ease-in-out infinite}
.orb3{width:280px;height:280px;background:radial-gradient(circle,rgba(244,63,94,.07),transparent 68%);top:50%;left:42%;animation:o3 11s ease-in-out infinite}
@keyframes o1{0%,100%{transform:translate(0,0)}50%{transform:translate(-30px,25px)}}
@keyframes o2{0%,100%{transform:translate(0,0)}50%{transform:translate(25px,-35px)}}
@keyframes o3{0%,100%{transform:translate(0,0)}50%{transform:translate(-15px,15px)}}

/* GRID */
.grid{
  position:fixed;inset:0;z-index:0;pointer-events:none;
  background:
    linear-gradient(rgba(0,255,204,.022) 1px,transparent 1px),
    linear-gradient(90deg,rgba(0,255,204,.022) 1px,transparent 1px);
  background-size:56px 56px;
  animation:gp 28s linear infinite;
}
@keyframes gp{0%{background-position:0 0}100%{background-position:56px 56px}}

/* WRAPPER */
.wrap{
  position:relative;z-index:10;
  display:grid;
  grid-template-columns:1fr auto 480px;
  width:min(1080px,95vw);
  background:var(--surface);
  border:1px solid var(--border2);
  box-shadow:
    0 0 0 1px rgba(0,255,204,.05),
    0 50px 130px rgba(0,0,0,.85),
    inset 0 1px 0 rgba(255,255,255,.03);
  animation:wIn .75s cubic-bezier(.16,1,.3,1) both;
  overflow:hidden;
}
@keyframes wIn{from{opacity:0;transform:scale(.95) translateY(20px)}to{opacity:1;transform:none}}

/* cut corners */
.wrap::before{content:'';position:absolute;top:0;left:0;width:0;height:0;border-top:26px solid var(--bg);border-right:26px solid transparent;z-index:30}
.wrap::after{content:'';position:absolute;bottom:0;right:0;width:0;height:0;border-bottom:26px solid var(--bg);border-left:26px solid transparent;z-index:30}

/* ── LEFT ── */
.lp{
  padding:52px 48px;
  display:flex;flex-direction:column;justify-content:space-between;
  background:linear-gradient(145deg,var(--surface2),var(--surface));
  position:relative;overflow:hidden;
}
/* scanning beam */
.lp::before{
  content:'';position:absolute;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,rgba(0,255,204,.5),transparent);
  animation:beam 5s ease-in-out infinite;z-index:1;
}
@keyframes beam{0%{top:0;opacity:.8}100%{top:100%;opacity:0}}

.logo{display:flex;align-items:center;gap:14px;animation:fup .7s .1s both}
.hex{
  width:46px;height:46px;
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  clip-path:polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);
  display:flex;align-items:center;justify-content:center;
  font-size:20px;flex-shrink:0;
  animation:hpulse 3s ease-in-out infinite;
}
@keyframes hpulse{0%,100%{filter:drop-shadow(0 0 6px rgba(0,255,204,.4))}50%{filter:drop-shadow(0 0 18px rgba(0,255,204,.8))}}

.logo-txt{
  font-family:'Syne',sans-serif;
  font-size:24px;font-weight:800;letter-spacing:3px;color:var(--white);
}
.logo-txt span{
  background:linear-gradient(90deg,var(--cyan),var(--cyan2));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
}
.logo-sub{font-size:9px;letter-spacing:5px;text-transform:uppercase;color:var(--text2);margin-left:60px;margin-top:4px;animation:fup .7s .15s both}

.big-title{
  font-family:'Syne',sans-serif;
  font-size:clamp(26px,3.2vw,42px);font-weight:800;
  line-height:1.1;color:var(--white);
  margin-top:36px;animation:fup .7s .25s both;
}
.big-title .grd{
  display:block;
  background:linear-gradient(90deg,var(--cyan),var(--cyan2),var(--purple));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-size:200% auto;
  animation:shimmer 4s linear infinite;
}
@keyframes shimmer{0%{background-position:0%}100%{background-position:200%}}

.desc{
  font-size:11px;color:var(--text2);line-height:1.85;margin-top:14px;
  max-width:360px;animation:fup .7s .35s both;
}

.stats{
  display:flex;gap:28px;padding:24px 0;
  border-top:1px solid var(--border);border-bottom:1px solid var(--border);
  margin-top:auto;animation:fup .7s .45s both;
}
.sv{font-family:'Syne',sans-serif;font-size:24px;font-weight:700;color:var(--white);line-height:1}
.sv em{color:var(--cyan);font-style:normal}
.sl{font-size:9px;letter-spacing:2px;text-transform:uppercase;color:var(--text2);margin-top:4px}

.feats{display:flex;flex-direction:column;gap:10px;animation:fup .7s .55s both}
.feat{display:flex;align-items:center;gap:10px;font-size:11px;color:var(--text2)}
.fd{width:5px;height:5px;border-radius:50%;flex-shrink:0;animation:blink 2s ease-in-out infinite}
.fd1{background:var(--cyan);box-shadow:0 0 8px var(--cyan)}
.fd2{background:var(--purple);box-shadow:0 0 8px var(--purple);animation-delay:.5s}
.fd3{background:var(--cyan2);box-shadow:0 0 8px var(--cyan2);animation-delay:1s}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}

/* ── VERTICAL DIVIDER ── */
.vd{
  width:1px;
  background:linear-gradient(to bottom,transparent 5%,var(--border2) 20%,rgba(0,255,204,.2) 50%,var(--border2) 80%,transparent 95%);
  flex-shrink:0;
}

/* ── RIGHT ── */
.rp{
  padding:52px 44px;
  display:flex;flex-direction:column;justify-content:center;
  position:relative;overflow:hidden;
}
.rp::before{
  content:'';position:absolute;top:0;left:15%;right:15%;height:2px;
  background:linear-gradient(90deg,transparent,var(--cyan),var(--purple),transparent);
  animation:tpulse 3s ease-in-out infinite;
}
@keyframes tpulse{0%,100%{opacity:.4}50%{opacity:1}}

/* corners */
.corn{position:absolute;width:16px;height:16px;opacity:.6}
.ctop-r{top:14px;right:14px;border-top:2px solid var(--purple);border-right:2px solid var(--purple)}
.cbot-l{bottom:14px;left:14px;border-bottom:2px solid var(--purple);border-left:2px solid var(--purple)}

.fhead{animation:fup .7s .2s both;margin-bottom:32px}
.ftag{
  font-size:9px;letter-spacing:4px;text-transform:uppercase;color:var(--cyan);
  margin-bottom:10px;display:flex;align-items:center;gap:8px;
}
.ftag::before{content:'';width:22px;height:1px;background:linear-gradient(90deg,var(--cyan),transparent)}
.ftitle{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:var(--white);letter-spacing:.5px}
.fsub{font-size:11px;color:var(--text2);margin-top:6px;line-height:1.6}

/* ERROR */
.err{
  background:rgba(244,63,94,.07);border:1px solid rgba(244,63,94,.22);
  border-left:3px solid var(--pink);
  padding:11px 15px;font-size:11px;color:#fb7185;
  margin-bottom:20px;display:flex;align-items:center;gap:10px;
  animation:shake .4s both;
}
@keyframes shake{0%{transform:translateX(-8px);opacity:0}50%{transform:translateX(4px)}100%{transform:translateX(0);opacity:1}}

/* FIELDS */
.field{margin-bottom:20px;animation:fup .6s both}
.fn1{animation-delay:.3s}.fn2{animation-delay:.4s}
.flbl{
  display:flex;align-items:center;justify-content:space-between;
  font-size:9px;letter-spacing:3px;text-transform:uppercase;color:var(--text2);margin-bottom:8px;
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
  position:absolute;left:15px;top:50%;transform:translateY(-50%);
  width:15px;height:15px;color:var(--text2);
  transition:color .3s;pointer-events:none;
}
.shell:focus-within .ico{color:var(--cyan)}

.finp{
  width:100%;
  background:rgba(255,255,255,.025);
  border:1px solid var(--border);
  color:var(--white);
  padding:14px 14px 14px 42px;
  font-family:'Space Mono',monospace;font-size:13px;
  outline:none;transition:all .3s;
}
.finp::placeholder{color:var(--text2)}
.finp:focus{
  border-color:rgba(0,255,204,.3);
  background:rgba(0,255,204,.035);
  box-shadow:0 0 0 1px rgba(0,255,204,.12),0 0 28px rgba(0,255,204,.05);
}

.fline{
  height:1px;
  background:linear-gradient(90deg,var(--cyan),var(--cyan2),var(--purple));
  transform:scaleX(0);transform-origin:left;
  transition:transform .4s cubic-bezier(.4,0,.2,1);
  margin-top:-1px;
}
.shell:focus-within .fline{transform:scaleX(1)}

.eye{
  position:absolute;right:13px;top:50%;transform:translateY(-50%);
  background:none;border:none;color:var(--text2);cursor:pointer;
  padding:4px;z-index:2;transition:color .3s;
}
.eye:hover{color:var(--cyan)}

/* REMEMBER ROW */
.mrow{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:26px;animation:fup .6s .5s both;
}
.cwrap{display:flex;align-items:center;gap:8px;cursor:pointer}
.cbox{
  width:15px;height:15px;border:1px solid var(--border2);
  background:transparent;appearance:none;cursor:pointer;
  position:relative;transition:all .3s;
}
.cbox:checked{background:var(--cyan);border-color:var(--cyan);box-shadow:0 0 10px rgba(0,255,204,.35)}
.cbox:checked::after{content:'✓';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:8px;font-weight:700;color:#000}
.clbl{font-size:10px;color:var(--text2)}
.flnk{font-size:10px;color:var(--cyan);text-decoration:none;border-bottom:1px solid transparent;transition:border-color .3s}
.flnk:hover{border-color:var(--cyan)}

/* SUBMIT BTN */
.sbtn{
  width:100%;padding:16px;
  background:transparent;
  border:1px solid rgba(0,255,204,.3);
  color:var(--white);
  font-family:'Syne',sans-serif;font-size:13px;font-weight:700;
  letter-spacing:4px;text-transform:uppercase;
  cursor:pointer;position:relative;overflow:hidden;
  transition:all .3s;margin-bottom:26px;
  animation:fup .6s .6s both;
}
.sbtn::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,var(--cyan),var(--cyan2),var(--purple));
  opacity:0;transition:opacity .3s;z-index:0;
}
.sbtn::after{
  content:'';position:absolute;top:0;left:-100%;
  width:100%;height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);
  transition:left .5s;z-index:1;
}
.sbtn:hover::before{opacity:1}
.sbtn:hover::after{left:100%}
.sbtn:hover{border-color:transparent;box-shadow:0 8px 40px rgba(0,255,204,.28),0 0 0 1px rgba(0,255,204,.15);transform:translateY(-2px)}
.sbtn:active{transform:translateY(0)}
.btxt{position:relative;z-index:2;display:flex;align-items:center;justify-content:center;gap:10px}
.spin{width:13px;height:13px;border:2px solid rgba(255,255,255,.2);border-top-color:var(--white);border-radius:50%;animation:sp .7s linear infinite;display:none}
@keyframes sp{to{transform:rotate(360deg)}}

/* OR */
.or{display:flex;align-items:center;gap:12px;margin-bottom:18px;animation:fup .6s .65s both}
.or::before,.or::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent)}
.ort{font-size:9px;letter-spacing:3px;text-transform:uppercase;color:var(--text2)}

/* REG LINK */
.rl{text-align:center;font-size:11px;color:var(--text2);animation:fup .6s .7s both}
.rl a{color:var(--cyan);text-decoration:none;font-weight:700;letter-spacing:1px;position:relative;transition:color .3s}
.rl a::after{content:'';position:absolute;bottom:-2px;left:0;width:0;height:1px;background:linear-gradient(90deg,var(--cyan),var(--purple));transition:width .3s}
.rl a:hover::after{width:100%}

/* STATUS BAR */
.sbar{position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--cyan),var(--cyan2),var(--purple));animation:sbIn .8s .9s both;transform-origin:left}
@keyframes sbIn{from{transform:scaleX(0)}to{transform:scaleX(1)}}

@keyframes fup{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

@media(max-width:780px){
  .lp,.vd{display:none}
  .wrap{grid-template-columns:1fr}
  .rp{padding:44px 28px}
}
</style>
</head>
<body>

<canvas id="cv"></canvas>
<div class="grid"></div>
<div class="orb orb1"></div>
<div class="orb orb2"></div>
<div class="orb orb3"></div>

<div class="wrap">

  <!-- LEFT -->
  <div class="lp">
    <div>
      <div class="logo">
        <div class="hex">⬡</div>
        <div class="logo-txt">PC<span>STORE</span></div>
      </div>
      <div class="logo-sub">Premium Tech — Toshkent</div>
    </div>

    <div>
      <h1 class="big-title">
        Kelajak<br>texnologiyasi
        <span class="grd">— siz uchun</span>
      </h1>
      <p class="desc">
        // Eng so'nggi kompyuterlar,<br>
        // aksessuarlar va elektronika.<br>
        // Tez yetkazib berish. 2 yil kafolat.
      </p>
    </div>

    <div class="stats">
      <div><div class="sv">500<em>+</em></div><div class="sl">Mahsulot</div></div>
      <div><div class="sv">10<em>k</em></div><div class="sl">Mijoz</div></div>
      <div><div class="sv">4.9<em>★</em></div><div class="sl">Reyting</div></div>
    </div>

    <div class="feats">
      <div class="feat"><div class="fd fd1"></div>Rasmiy distribyutor — Dell, ASUS, Lenovo</div>
      <div class="feat"><div class="fd fd2"></div>24 soat ichida yetkazib berish</div>
      <div class="feat"><div class="fd fd3"></div>Onlayn texnik yordam — 7/24</div>
    </div>
  </div>

  <!-- DIVIDER -->
  <div class="vd"></div>

  <!-- RIGHT -->
  <div class="rp">
    <div class="corn ctop-r"></div>
    <div class="corn cbot-l"></div>

    <div class="fhead">
      <div class="ftag">// kirish protokoli</div>
      <div class="ftitle">Tizimga kirish</div>
      <div class="fsub">Hisobingiz ma'lumotlarini kiriting</div>
    </div>

    <?php if($error): ?>
    <div class="err">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" id="frm">
      <div class="field fn1">
        <div class="flbl"><span>Foydalanuvchi nomi</span><span class="ftag2">REQ</span></div>
        <div class="shell">
          <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          <input class="finp" type="text" name="username" placeholder="username" required
            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
          <div class="fline"></div>
        </div>
      </div>

      <div class="field fn2">
        <div class="flbl"><span>Parol</span><span class="ftag2">REQ</span></div>
        <div class="shell">
          <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
          <input class="finp" type="password" id="pi" name="password" placeholder="••••••••" required>
          <div class="fline"></div>
          <button type="button" class="eye" onclick="document.getElementById('pi').type=document.getElementById('pi').type=='password'?'text':'password'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="mrow">
        <label class="cwrap">
          <input type="checkbox" class="cbox" name="remember">
          <span class="clbl">Eslab qolish</span>
        </label>
        <a href="#" class="flnk">Parolni unutdingizmi?</a>
      </div>

      <button type="submit" class="sbtn" id="sb">
        <span class="btxt">
          <span class="spin" id="sp"></span>
          <span id="bl">KIRISH</span>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
          </svg>
        </span>
      </button>
    </form>

    <div class="or"><span class="ort">yoki</span></div>
    <p class="rl">Hisob yo'qmi? — <a href="register.php">Ro'yxatdan o'tish</a></p>
    <div class="sbar"></div>
  </div>
</div>

<script>
/* PARTICLES */
(()=>{
  const c=document.getElementById('cv'),x=c.getContext('2d');
  let W,H,P=[];
  function sz(){W=c.width=innerWidth;H=c.height=innerHeight}
  sz();addEventListener('resize',sz);
  const r=(a,b)=>a+(b-a)*Math.random();
  for(let i=0;i<70;i++){
    P.push({x:r(0,1),y:r(0,1),vx:r(-.2,.2),vy:r(-.35,-.08),
      rad:r(.4,1.8),life:r(0,1),spd:r(.003,.009),
      c:Math.random()>.5?'0,255,204':'139,92,246'});
  }
  function draw(){
    x.clearRect(0,0,W,H);
    P.forEach(p=>{
      p.life+=p.spd;
      if(p.life>1){p.life=0;p.x=r(0,1);p.y=1.05}
      const a=Math.sin(p.life*Math.PI)*.45;
      x.beginPath();
      x.arc(p.x*W,(p.y-(p.life*.25))*H,p.rad,0,Math.PI*2);
      x.fillStyle=`rgba(${p.c},${a})`;
      x.shadowBlur=5;x.shadowColor=`rgba(${p.c},.25)`;
      x.fill();
    });
    requestAnimationFrame(draw);
  }
  draw();
})();

/* SUBMIT */
document.getElementById('frm').addEventListener('submit',()=>{
  document.getElementById('sp').style.display='block';
  document.getElementById('bl').textContent='Tekshirilmoqda...';
  document.getElementById('sb').disabled=true;
});
</script>

<?php  
include '../partials/end.php'; 

?>
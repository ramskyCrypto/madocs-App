@php
    $names = ['canvas','card','soft','ink','mute','line','line2','brand','brand-hover','brand-soft','brand-text','ok-solid','ok-soft','ok-text','warn-soft','warn-text','bad-solid','bad-soft','bad-text'];
    $light = '--canvas:250 250 250;--card:255 255 255;--soft:244 244 245;--ink:23 23 23;--mute:102 102 102;--line:234 234 234;--line2:214 214 214;--brand:94 106 210;--brand-hover:76 88 190;--brand-soft:238 240 252;--brand-text:62 72 160;--ok-solid:15 118 110;--ok-soft:221 246 241;--ok-text:15 110 100;--warn-soft:254 243 214;--warn-text:133 77 14;--bad-solid:197 48 48;--bad-soft:254 226 226;--bad-text:153 27 27;';
    $dark = '--canvas:10 10 10;--card:20 20 20;--soft:30 30 30;--ink:237 237 237;--mute:161 161 161;--line:42 42 42;--line2:58 58 58;--brand:94 106 210;--brand-hover:82 94 196;--brand-soft:30 33 66;--brand-text:170 178 248;--ok-solid:15 118 110;--ok-soft:12 48 44;--ok-text:94 224 205;--warn-soft:58 42 10;--warn-text:250 204 100;--bad-solid:197 48 48;--bad-soft:64 20 20;--bad-text:252 165 165;';
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&display=swap" rel="stylesheet">
<script>
try { var t = localStorage.getItem('md_theme'); if (t === 'dark' || t === 'light') document.documentElement.setAttribute('data-theme', t); } catch (e) {}
window.mdStore = {
    get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
    set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
};
window.mdToggleTheme = function () {
    var r = document.documentElement;
    var cur = r.getAttribute('data-theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    var next = cur === 'dark' ? 'light' : 'dark';
    r.setAttribute('data-theme', next);
    window.mdStore.set('md_theme', next);
};
</script>
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<script>
tailwind.config = { theme: { extend: {
    fontFamily: { sans: ['Geist', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
    colors: {
@foreach($names as $n)
        '{{ $n }}': 'rgb(var(--{{ $n }}) / <alpha-value>)',
@endforeach
        indigo: { 50: '#EEF0FC', 100: '#E0E4F8', 600: '#5E6AD2', 700: '#4C58BE' }
    }
} } }
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
<style>
:root{ {!! $light !!} color-scheme: light; }
@media (prefers-color-scheme: dark){ :root:not([data-theme="light"]){ {!! $dark !!} color-scheme: dark; } }
:root[data-theme="dark"]{ {!! $dark !!} color-scheme: dark; }
@media print{ :root, :root[data-theme="dark"]{ {!! $light !!} color-scheme: light; } }

[x-cloak]{display:none !important}
html{-webkit-text-size-adjust:100%}
body{background:rgb(var(--canvas));color:rgb(var(--ink));font-family:Geist,Inter,ui-sans-serif,system-ui,sans-serif}
:focus-visible{outline:2px solid rgb(var(--brand));outline-offset:2px}
.skip{position:absolute;left:-9999px;top:0;z-index:100;background:rgb(var(--card));padding:.5rem 1rem;border-radius:.5rem}
.skip:focus{left:.75rem;top:.75rem}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;min-height:2.5rem;padding:0 1rem;border-radius:.625rem;border:1px solid transparent;font-size:.875rem;font-weight:500;line-height:1.25rem;cursor:pointer;text-decoration:none;white-space:nowrap;transition:background-color .15s,border-color .15s,filter .15s}
@media (pointer:coarse){.btn{min-height:2.75rem}}
.btn-primary{background:rgb(var(--brand));color:#fff}
.btn-primary:hover{background:rgb(var(--brand-hover))}
.btn-secondary{background:rgb(var(--card));border-color:rgb(var(--line2));color:rgb(var(--ink))}
.btn-secondary:hover{background:rgb(var(--soft))}
.btn-ghost{color:rgb(var(--mute))}
.btn-ghost:hover{background:rgb(var(--soft));color:rgb(var(--ink))}
.btn-ok{background:rgb(var(--ok-solid));color:#fff}
.btn-ok:hover{filter:brightness(1.12)}
.btn-danger{background:rgb(var(--bad-soft));color:rgb(var(--bad-text))}
.btn-danger:hover{filter:brightness(.96)}
.btn-sm{min-height:2rem;padding:0 .75rem;font-size:.8125rem}
.btn-block{width:100%}

.input,input.input,select.input,textarea.input{display:block;width:100%;min-height:2.5rem;padding-top:.5rem;padding-bottom:.5rem;padding-left:.75rem;padding-right:.75rem;background-color:rgb(var(--card));color:rgb(var(--ink));border:1px solid rgb(var(--line2));border-radius:.625rem;font-size:1rem;line-height:1.5rem;box-shadow:none}
select.input{padding-right:2.25rem}
@media (min-width:640px){.input,input.input,select.input,textarea.input{font-size:.875rem;line-height:1.25rem}}
.input:focus,input.input:focus,select.input:focus,textarea.input:focus{border-color:rgb(var(--brand));box-shadow:0 0 0 3px rgb(var(--brand) / .22);outline:none}
.input::placeholder{color:rgb(var(--mute));opacity:.75}
.input-icon{padding-left:2.25rem !important}
.input-file{padding:.375rem !important}
.label{display:block;margin-bottom:.375rem;font-size:.8125rem;font-weight:500}
.hint{margin-top:.375rem;font-size:.75rem;color:rgb(var(--mute))}
.err{margin-top:.375rem;font-size:.75rem;font-weight:500;color:rgb(var(--bad-text))}

.card{background:rgb(var(--card));border:1px solid rgb(var(--line));border-radius:.875rem}
.chip{display:inline-flex;align-items:center;gap:.5rem;min-height:2.25rem;padding:0 .875rem;border-radius:999px;border:1px solid rgb(var(--line2));background:rgb(var(--card));color:rgb(var(--ink));font-size:.875rem;text-decoration:none;transition:background-color .15s}
.chip:hover{background:rgb(var(--soft))}
.chip b{font-weight:600}
.chip-on{background:rgb(var(--brand-soft));border-color:transparent;color:rgb(var(--brand-text));font-weight:500}
.chip-on:hover{background:rgb(var(--brand-soft))}

.nav{display:flex;align-items:center;gap:.75rem;min-height:2.25rem;padding:0 .75rem;border-radius:.625rem;font-size:.875rem;color:rgb(var(--mute));text-decoration:none;transition:background-color .15s,color .15s}
@media (pointer:coarse){.nav{min-height:2.75rem}}
.nav:hover{background:rgb(var(--soft));color:rgb(var(--ink))}
.nav-on,.nav-on:hover{background:rgb(var(--brand-soft));color:rgb(var(--brand-text));font-weight:500}

.tbl{width:100%;border-collapse:collapse;font-size:.875rem}
.tbl th{padding:.625rem 1rem;text-align:left;font-size:.75rem;font-weight:500;color:rgb(var(--mute));background:rgb(var(--soft));white-space:nowrap}
.tbl td{padding:.75rem 1rem;border-top:1px solid rgb(var(--line));vertical-align:middle}
.tbl tbody tr:hover td{background:rgb(var(--soft) / .6)}
.tbl .tc{text-align:center}
@media (max-width:639px){
  .tbl-stack thead{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)}
  .tbl-stack,.tbl-stack tbody,.tbl-stack tr,.tbl-stack td{display:block;width:100%}
  .tbl-stack tr{padding:.75rem 1rem;border-top:1px solid rgb(var(--line))}
  .tbl-stack tr:first-child{border-top:0}
  .tbl-stack td{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:.3rem 0;border:0;text-align:right}
  .tbl-stack td::before{content:attr(data-label);flex:none;max-width:42%;text-align:left;font-size:.75rem;color:rgb(var(--mute))}
  .tbl-stack td[data-label=""]::before{content:none}
  .tbl-stack td.aksi{justify-content:flex-end;padding-top:.5rem}
  .tbl-stack tbody tr:hover td{background:transparent}
}

.flash{display:flex;align-items:flex-start;gap:.75rem;padding:.75rem 1rem;border-radius:.75rem;font-size:.875rem}
.flash-ok{background:rgb(var(--ok-soft));color:rgb(var(--ok-text))}
.flash-bad{background:rgb(var(--bad-soft));color:rgb(var(--bad-text))}
.sub:empty{display:none}
.theme-sun{display:none}
:root[data-theme="dark"] .theme-sun{display:inline}
:root[data-theme="dark"] .theme-moon{display:none}
@media (prefers-color-scheme: dark){
  :root:not([data-theme="light"]) .theme-sun{display:inline}
  :root:not([data-theme="light"]) .theme-moon{display:none}
}
@media (prefers-reduced-motion: reduce){*{transition:none !important}}
</style>

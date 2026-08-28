/**
 * Folyamatos hatter-reteg (WebGL).
 * ----------------------------------------------------------------------------
 * Egyetlen teljes kepernyos haromszog, egyetlen fragmens-arnyalo: nagyon lassan
 * sodrodo szemcse es egy alig lathato vilagossag-gradiens. Ennyi. Nincs geometria,
 * nincs kamera, nincs jelenetgraf - ezert nem kell hozza 3D-motor sem. A teljes
 * fajl par kilobajt azzal szemben, amit egy kesz konyvtar behozna, es pontosan
 * annyit tud, amennyi ezen az oldalon indokolt.
 *
 * Miert nem CSS: az animalt background-position vagy filter minden kepkockan
 * UJRAFESTETI a teljes hattert a fo szalon. Ez a reteg a GPU-n keszul el, a fo
 * szal erintese nelkul.
 *
 * Hasznalat - a jelolesben:
 *     <canvas class="ambient" data-ambient aria-hidden="true"></canvas>
 * es a betoltes a layoutban vagy oldalankent:
 *     <script src="{{ \App\Support\Asset::url('assets/js/ambient.js') }}" defer></script>
 *
 * A reteg SZANDEKOSAN opcionalis: alapbol nincs bekotve. Egy monokrom,
 * tipografia-vezerelt oldalon a hatter feladata az, hogy ne vonja el a
 * figyelmet - ezert nagyon halk, es ezert kell tudatosan bekapcsolni.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var canvas = document.querySelector('[data-ambient]');
        if (!canvas) return;

        var motion = window.blcktMotion;

        // Csokkentett mozgas mellett a reteg el sem indul: egy vegtelen,
        // folyamatos mozgas eppen az, amit a beallitas kizar.
        if (motion && motion.view.reduced) return;
        if (!motion && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var gl = canvas.getContext('webgl', {
            alpha: true,
            antialias: false,
            depth: false,
            stencil: false,
            powerPreference: 'low-power'
        });

        // Nincs WebGL (regi gep, kikapcsolt gyorsitas): a hatter marad ures.
        // Az oldal enelkul is teljes ertekű, ezert nincs tartalek megoldas.
        if (!gl) return;

        /* ---------------------------------------------------------------
           Arnyalok
           A csucs-arnyalo egyetlen, a kepernyonel nagyobb haromszoget rajzol.
           Ez olcsobb, mint ket haromszogbol osszerakni egy negyzetet: nincs
           atlo, amin a GPU ketszer dolgozna.
           --------------------------------------------------------------- */
        var vertexSource = [
            'attribute vec2 a_pos;',
            'void main() {',
            '  gl_Position = vec4(a_pos, 0.0, 1.0);',
            '}'
        ].join('\n');

        var fragmentSource = [
            'precision mediump float;',
            'uniform vec2 u_size;',
            'uniform float u_time;',
            'uniform float u_ink;',      // 0 = vilagos mod, 1 = sotet mod
            '',
            // Olcso al-veletlen. Nem kell jo minosegu zaj: a szemcse
            // feladata csak annyi, hogy megtorje a sik feluletet.
            'float hash(vec2 p) {',
            '  return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453123);',
            '}',
            '',
            'void main() {',
            '  vec2 uv = gl_FragCoord.xy / u_size;',
            '',
            // Nagyon lassu, atlos sodrodas - a mozgas hatarozottan a
            // eszrevehetoseg kuszobe ALATT marad.
            '  vec2 drift = vec2(u_time * 0.006, u_time * -0.004);',
            '  float grain = hash(floor((uv + drift) * u_size * 0.5));',
            '',
            // Halk vilagossag-gradiens felulrol lefele.
            '  float glow = smoothstep(0.0, 1.0, 1.0 - uv.y) * 0.5;',
            '',
            '  float value = glow * 0.05 + grain * 0.035;',
            '  gl_FragColor = vec4(vec3(u_ink), value);',
            '}'
        ].join('\n');

        function compile(type, source) {
            var shader = gl.createShader(type);
            gl.shaderSource(shader, source);
            gl.compileShader(shader);

            if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
                gl.deleteShader(shader);
                return null;
            }

            return shader;
        }

        var vertex = compile(gl.VERTEX_SHADER, vertexSource);
        var fragment = compile(gl.FRAGMENT_SHADER, fragmentSource);
        if (!vertex || !fragment) return;

        var program = gl.createProgram();
        gl.attachShader(program, vertex);
        gl.attachShader(program, fragment);
        gl.linkProgram(program);

        if (!gl.getProgramParameter(program, gl.LINK_STATUS)) return;

        gl.useProgram(program);

        var buffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);

        var position = gl.getAttribLocation(program, 'a_pos');
        gl.enableVertexAttribArray(position);
        gl.vertexAttribPointer(position, 2, gl.FLOAT, false, 0, 0);

        var uSize = gl.getUniformLocation(program, 'u_size');
        var uTime = gl.getUniformLocation(program, 'u_time');
        var uInk = gl.getUniformLocation(program, 'u_ink');

        gl.enable(gl.BLEND);
        gl.blendFunc(gl.SRC_ALPHA, gl.ONE_MINUS_SRC_ALPHA);

        /* ---------------------------------------------------------------
           Meret
           A pixelarany 1.5-nel el van vagva: egy szemcses hatteren a 3x
           felbontas nem lathato, viszont haromszor annyi keppontot festetne.
           --------------------------------------------------------------- */
        var width = 0;
        var height = 0;

        function resize() {
            var ratio = Math.min(window.devicePixelRatio || 1, 1.5);
            var w = Math.round(canvas.clientWidth * ratio);
            var h = Math.round(canvas.clientHeight * ratio);

            if (w === width && h === height) return;

            width = w;
            height = h;
            canvas.width = w;
            canvas.height = h;
            gl.viewport(0, 0, w, h);
            gl.uniform2f(uSize, w, h);
        }

        function inkValue() {
            return document.documentElement.getAttribute('data-theme') === 'dark' ? 1 : 0;
        }

        /* ---------------------------------------------------------------
           Rajzolas
           A lapfulet elhagyva a bongeszo amugy sem hivja a rAF-et, de a
           lathatosag-figyelo ezt egyertelmuve teszi: a hatter nem eszik
           akkumulatort a hatterben.
           --------------------------------------------------------------- */
        var start = window.performance.now();
        var running = false;
        var frame = 0;

        function render(now) {
            frame = 0;
            resize();
            gl.uniform1f(uTime, (now - start) / 1000);
            gl.uniform1f(uInk, inkValue());
            gl.drawArrays(gl.TRIANGLES, 0, 3);
            if (running) frame = window.requestAnimationFrame(render);
        }

        function play() {
            if (running) return;
            running = true;
            frame = window.requestAnimationFrame(render);
        }

        function pause() {
            running = false;
            if (frame) window.cancelAnimationFrame(frame);
            frame = 0;
        }

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) pause();
            else play();
        });

        // A vaszon kigordulhat a nezetbol - akkor sincs mit rajzolni.
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) play();
                else pause();
            }).observe(canvas);
        } else {
            play();
        }

        // Ha kesobb kapcsoljak be a csokkentett mozgast, alljunk le.
        if (motion) {
            motion.onReduceChange(function (reduced) {
                if (reduced) pause();
            });
        }

        play();
    });
})();

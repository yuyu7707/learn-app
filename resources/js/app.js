/*
 * クリックしたときの、やさしい効果音。
 * 音のファイルは使わず、ブラウザの Web Audio で小さな音を作る。
 * 会場のスピーカーやパソコンでも聞こえるよう、木琴のような少し太い音（三角波）にし、
 * 鳴る長さは短くしてうるさくならないようにする。
 * オン・オフはこの端末に覚える（はじめはオン）。
 */
const STORAGE_KEY = 'learn-sound';

let audioContext = null;

function soundEnabled() {
    try {
        return localStorage.getItem(STORAGE_KEY) !== 'off';
    } catch {
        return true;
    }
}

function setSoundEnabled(enabled) {
    try {
        localStorage.setItem(STORAGE_KEY, enabled ? 'on' : 'off');
    } catch {
        // 保存できない端末では、この画面のあいだだけ切り替える
    }
}

// 1つの音（高さ Hz、始まり秒、長さ秒、音量 0〜1、音色）
function tone(frequency, start, duration, volume = 0.3, type = 'triangle') {
    const oscillator = audioContext.createOscillator();
    const gain = audioContext.createGain();
    const at = audioContext.currentTime + start;

    oscillator.type = type;
    oscillator.frequency.value = frequency;
    gain.gain.setValueAtTime(0.0001, at);
    gain.gain.exponentialRampToValueAtTime(volume, at + 0.01);
    gain.gain.exponentialRampToValueAtTime(0.0001, at + duration);

    oscillator.connect(gain).connect(audioContext.destination);
    oscillator.start(at);
    oscillator.stop(at + duration);
}

/*
 * 猫の鳴き声 1回分。高さを上げてから下げ、こもった音にして鳴き声に近づける。
 * pitches：[はじめ, 上がりきり, いちばん高い, おわり] の高さ Hz
 * length：長さ（秒）、start：鳴きはじめ（秒後）
 */
function meow(pitches, length, start = 0, volume = 0.22) {
    const [from, rise, peak, to] = pitches;
    const oscillator = audioContext.createOscillator();
    const filter = audioContext.createBiquadFilter();
    const gain = audioContext.createGain();
    const at = audioContext.currentTime + start;

    oscillator.type = 'sawtooth';
    oscillator.frequency.setValueAtTime(from, at);
    oscillator.frequency.linearRampToValueAtTime(rise, at + length * 0.22);
    oscillator.frequency.linearRampToValueAtTime(peak, at + length * 0.45);
    oscillator.frequency.exponentialRampToValueAtTime(to, at + length);

    filter.type = 'lowpass';
    filter.frequency.setValueAtTime(1200, at);
    filter.frequency.linearRampToValueAtTime(2200, at + length * 0.3);
    filter.frequency.linearRampToValueAtTime(1000, at + length);

    gain.gain.setValueAtTime(0.0001, at);
    gain.gain.exponentialRampToValueAtTime(volume, at + 0.05);
    gain.gain.setValueAtTime(volume, at + length * 0.6);
    gain.gain.exponentialRampToValueAtTime(0.0001, at + length);

    oscillator.connect(filter).connect(gain).connect(audioContext.destination);
    oscillator.start(at);
    oscillator.stop(at + length);
}

// 鳴き声の種類。押すたびに順番に変わる
const MEOWS = [
    () => meow([480, 780, 860, 520], 0.7), // ニャー
    () => meow([700, 1000, 1050, 820], 0.28), // ミャッ（子猫）
    () => meow([450, 760, 900, 430], 1.0), // ニャ〜オ（甘える）
    () => {
        meow([560, 860, 900, 640], 0.22); // ニャニャッ（2回）
        meow([600, 900, 960, 680], 0.24, 0.26);
    },
    () => meow([340, 560, 620, 400], 0.8), // ンニャー（低め）
];

let meowIndex = 0;

function nextMeow() {
    MEOWS[meowIndex % MEOWS.length]();
    meowIndex++;
}

// pon：選んだとき（ポン） / pikon：つぎへ進むとき（ピコン） / nya：ミロを押したとき（ニャー）
function play(name) {
    if (!soundEnabled()) {
        return;
    }

    const AudioContextClass = window.AudioContext || window.webkitAudioContext;

    if (!AudioContextClass) {
        return;
    }

    audioContext ??= new AudioContextClass();
    audioContext.resume();

    if (name === 'pikon') {
        tone(659, 0, 0.2, 0.32);
        tone(988, 0.12, 0.4, 0.32);
        tone(1976, 0.12, 0.2, 0.06, 'sine');
    } else if (name === 'nya') {
        nextMeow();
    } else {
        tone(784, 0, 0.35, 0.35);
        tone(1568, 0, 0.18, 0.08, 'sine');
    }
}

function refreshToggles() {
    const enabled = soundEnabled();

    document.querySelectorAll('[data-sound-toggle]').forEach((button) => {
        button.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        button.querySelector('[data-sound-label]').textContent = enabled ? '🔔 音：オン' : '🔕 音：オフ';
    });
}

document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-sound-toggle]');

    if (toggle) {
        setSoundEnabled(!soundEnabled());
        refreshToggles();
        play('pon');

        return;
    }

    if (event.target.closest('.btn-next')) {
        play('pikon');

        return;
    }

    const target = event.target.closest('[data-sound]');

    if (target) {
        play(target.dataset.sound);
    }
});

document.addEventListener('DOMContentLoaded', refreshToggles);
document.addEventListener('livewire:navigated', refreshToggles);

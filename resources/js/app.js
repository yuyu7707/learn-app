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

// pon：選んだとき（ポン） / pikon：つぎへ進むとき（ピコン）
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

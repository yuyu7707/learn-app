/*
 * クリックしたときの、やさしい効果音。
 * 音のファイルは使わず、ブラウザの Web Audio で小さな音を作る。
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

// 1つの音（高さ Hz、始まり秒、長さ秒）
function tone(frequency, start, duration) {
    const oscillator = audioContext.createOscillator();
    const gain = audioContext.createGain();
    const at = audioContext.currentTime + start;

    oscillator.type = 'sine';
    oscillator.frequency.value = frequency;
    gain.gain.setValueAtTime(0.0001, at);
    gain.gain.exponentialRampToValueAtTime(0.15, at + 0.02);
    gain.gain.exponentialRampToValueAtTime(0.0001, at + duration);

    oscillator.connect(gain).connect(audioContext.destination);
    oscillator.start(at);
    oscillator.stop(at + duration);
}

// pon：選んだとき / pikon：つぎへ進むとき
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
        tone(660, 0, 0.18);
        tone(990, 0.1, 0.3);
    } else {
        tone(880, 0, 0.25);
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

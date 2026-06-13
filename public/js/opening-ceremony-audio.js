/**
 * Opening ceremony audio — standby background music (local MP3 loop).
 */
(function (global) {
  'use strict';

  let musicUrl = '';
  let bgAudio = null;
  let unlocked = false;
  let muted = false;

  const BG_VOL = 0.55;

  function configure(options) {
    if (options && options.musicUrl) {
      musicUrl = options.musicUrl;
      if (bgAudio) {
        bgAudio.pause();
        bgAudio = null;
      }
    }
  }

  function ensureBgAudio() {
    if (!musicUrl) return null;
    if (!bgAudio) {
      bgAudio = new Audio(musicUrl);
      bgAudio.loop = true;
      bgAudio.preload = 'auto';
      bgAudio.volume = BG_VOL;
    }
    return bgAudio;
  }

  function applyMute() {
    if (bgAudio) bgAudio.muted = muted;
  }

  function unlock() {
    if (unlocked) return Promise.resolve();
    ensureBgAudio();
    unlocked = true;
    applyMute();
    return Promise.resolve();
  }

  function setMuted(value) {
    muted = !!value;
    applyMute();
  }

  function isMuted() {
    return muted;
  }

  function isUnlocked() {
    return unlocked;
  }

  function startBackground() {
    if (!unlocked || !musicUrl) return;
    const track = ensureBgAudio();
    if (!track || !track.paused) return;
    track.volume = BG_VOL;
    const playAttempt = track.play();
    if (playAttempt && typeof playAttempt.catch === 'function') {
      playAttempt.catch(() => {});
    }
  }

  function stopBackground() {
    if (!bgAudio) return;
    bgAudio.pause();
    bgAudio.currentTime = 0;
  }

  function isBackgroundPlaying() {
    return !!(bgAudio && !bgAudio.paused);
  }

  global.OpeningCeremonyAudio = {
    configure,
    unlock,
    startBackground,
    stopBackground,
    setMuted,
    isMuted,
    isUnlocked,
    isBackgroundPlaying,
  };
})(window);

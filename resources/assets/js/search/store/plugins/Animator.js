"use strict";

export default store => {
  store.subscribe((mutation, rootState) => {
    if (mutation.type === 'Appearance/wheel' || mutation.type === 'Appearance/scroll') {
      const state = rootState.Appearance;

      const from = state.hotelsScrollTop;
      const till = window.till = state.hotelsScrollTopTarget;

      if (state.pageYStart) {
        store.commit('Appearance/scrollChange', till);
        return;
      }

      const duration = 100;
      const iterations = 20;

      let index = window.index = Math.floor(Math.random() * Math.random() * Math.random() * 1000000000000);

      for (let i = 1; i <= iterations; ++i) {
        const tick = i / iterations;
        const scrollTopNew = Math.floor(from + (till - from) * tick);

        const fn = () => {
          if (index !== window.index) return;
          store.commit('Appearance/scrollChange', scrollTopNew);
        };

        setTimeout(fn, duration * tick);
      }
    }
  });
};
